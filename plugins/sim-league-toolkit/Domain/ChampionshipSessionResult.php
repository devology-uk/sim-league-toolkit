<?php

  namespace SLTK\Domain;

  use Exception;
  use SLTK\Core\Constants;
  use SLTK\Database\Repositories\ChampionshipSessionResultsRepository;
  use SLTK\Domain\Abstractions\AggregateRoot;
  use SLTK\Domain\Abstractions\Deletable;
  use SLTK\Domain\Abstractions\ProvidesPersistableArray;
  use SLTK\Domain\Abstractions\Saveable;
  use SLTK\Domain\Traits\HasIdentity;
  use SLTK\Domain\Traits\HasResultEntrantFields;
  use SLTK\Domain\Traits\HasSessionResultFields;
  use stdClass;

  class ChampionshipSessionResult implements AggregateRoot, Deletable, ProvidesPersistableArray, Saveable {
    use HasIdentity;
    use HasResultEntrantFields;
    use HasSessionResultFields;

    private int $championshipEntryId = Constants::DEFAULT_ID;
    private string $className = '';

    /**
     * @throws Exception
     */
    public static function delete(int $id): void {
      ChampionshipSessionResultsRepository::delete($id);
    }

    public static function fromStdClass(?stdClass $data): ?self {
      if (!$data) {
        return null;
      }

      $result = new self();
      $result->setId((int)$data->id);
      $result->setChampionshipEntryId((int)$data->championshipEntryId);
      $result->setClassName($data->className ?? '');
      $result->hydrateResultEntrantFieldsFromStdClass($data);
      $result->hydrateSessionResultFields($data);

      return $result;
    }

    /**
     * @throws Exception
     */
    public static function get(int $id): ?self {
      return self::fromStdClass(ChampionshipSessionResultsRepository::getById($id));
    }

    /**
     * @return ChampionshipSessionResult[]
     * @throws Exception
     */
    public static function listByEventSession(int $eventSessionId): array {
      $results = ChampionshipSessionResultsRepository::listByEventSessionId($eventSessionId);

      return array_map(fn($row) => self::fromStdClass($row), $results);
    }

    public function getChampionshipEntryId(): int {
      return $this->championshipEntryId;
    }

    public function setChampionshipEntryId(int $value): void {
      $this->championshipEntryId = $value;
    }

    public function getClassName(): string {
      return $this->className;
    }

    private function setClassName(string $value): void {
      $this->className = $value;
    }

    /**
     * @throws Exception
     */
    public function save(): self {
      if (!$this->hasId()) {
        $this->setId(ChampionshipSessionResultsRepository::add($this->toArray()));
      } else {
        ChampionshipSessionResultsRepository::update($this->getId(), $this->toArray());
      }

      return $this;
    }

    public function toArray(): array {
      return array_merge(
        [
          'eventSessionId' => $this->getEventSessionId(),
          'championshipEntryId' => $this->getChampionshipEntryId(),
        ],
        $this->sessionResultFieldsToArray()
      );
    }

    public function toDto(): array {
      return array_merge(
        ['id' => $this->getId()],
        $this->resultEntrantFieldsToDto(),
        [
          'championshipEntryId' => $this->getChampionshipEntryId(),
          'className' => $this->getClassName(),
        ],
        $this->sessionResultFieldsToArray()
      );
    }
  }
