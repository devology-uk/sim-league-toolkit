<?php

  namespace SLTK\Domain;

  use Exception;
  use SLTK\Core\Constants;
  use SLTK\Database\Repositories\StandaloneSessionResultsRepository;
  use SLTK\Domain\Abstractions\AggregateRoot;
  use SLTK\Domain\Abstractions\Deletable;
  use SLTK\Domain\Abstractions\ProvidesPersistableArray;
  use SLTK\Domain\Abstractions\Saveable;
  use SLTK\Domain\Traits\HasIdentity;
  use SLTK\Domain\Traits\HasResultEntrantFields;
  use SLTK\Domain\Traits\HasSessionResultFields;
  use stdClass;

  class StandaloneSessionResult implements AggregateRoot, Deletable, ProvidesPersistableArray, Saveable {
    use HasIdentity;
    use HasResultEntrantFields;
    use HasSessionResultFields;

    private ?string $className = null;
    private int $standaloneEventEntryId = Constants::DEFAULT_ID;

    /**
     * @throws Exception
     */
    public static function delete(int $id): void {
      StandaloneSessionResultsRepository::delete($id);
    }

    public static function fromStdClass(?stdClass $data): ?self {
      if (!$data) {
        return null;
      }

      $result = new self();
      $result->setId((int)$data->id);
      $result->setStandaloneEventEntryId((int)$data->standaloneEventEntryId);
      $result->setClassName($data->className ?? null);
      $result->hydrateResultEntrantFieldsFromStdClass($data);
      $result->hydrateSessionResultFields($data);

      return $result;
    }

    /**
     * @throws Exception
     */
    public static function get(int $id): ?self {
      return self::fromStdClass(StandaloneSessionResultsRepository::getById($id));
    }

    /**
     * @return StandaloneSessionResult[]
     * @throws Exception
     */
    public static function listByEventSession(int $eventSessionId): array {
      $results = StandaloneSessionResultsRepository::listByEventSessionId($eventSessionId);

      return array_map(fn($row) => self::fromStdClass($row), $results);
    }

    public function getClassName(): ?string {
      return $this->className;
    }

    private function setClassName(?string $value): void {
      $this->className = $value;
    }

    public function getStandaloneEventEntryId(): int {
      return $this->standaloneEventEntryId;
    }

    public function setStandaloneEventEntryId(int $value): void {
      $this->standaloneEventEntryId = $value;
    }

    /**
     * @throws Exception
     */
    public function save(): self {
      if (!$this->hasId()) {
        $this->setId(StandaloneSessionResultsRepository::add($this->toArray()));
      } else {
        StandaloneSessionResultsRepository::update($this->getId(), $this->toArray());
      }

      return $this;
    }

    public function toArray(): array {
      return array_merge(
        [
          'eventSessionId' => $this->getEventSessionId(),
          'standaloneEventEntryId' => $this->getStandaloneEventEntryId(),
        ],
        $this->sessionResultFieldsToArray()
      );
    }

    public function toDto(): array {
      return array_merge(
        ['id' => $this->getId()],
        $this->resultEntrantFieldsToDto(),
        [
          'standaloneEventEntryId' => $this->getStandaloneEventEntryId(),
          'className' => $this->getClassName(),
        ],
        $this->sessionResultFieldsToArray()
      );
    }
  }
