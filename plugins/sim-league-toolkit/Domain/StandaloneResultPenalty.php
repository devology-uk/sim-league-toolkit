<?php

  namespace SLTK\Domain;

  use Exception;
  use SLTK\Core\Constants;
  use SLTK\Database\Repositories\StandaloneResultPenaltiesRepository;
  use SLTK\Domain\Abstractions\AggregateRoot;
  use SLTK\Domain\Abstractions\Deletable;
  use SLTK\Domain\Abstractions\ProvidesPersistableArray;
  use SLTK\Domain\Abstractions\Saveable;
  use SLTK\Domain\Traits\HasIdentity;
  use SLTK\Domain\Traits\HasPenaltyFields;
  use stdClass;

  class StandaloneResultPenalty implements AggregateRoot, Deletable, ProvidesPersistableArray, Saveable {
    use HasIdentity;
    use HasPenaltyFields;

    private int $standaloneSessionResultId = Constants::DEFAULT_ID;

    /**
     * @throws Exception
     */
    public static function delete(int $id): void {
      StandaloneResultPenaltiesRepository::delete($id);
    }

    public static function fromStdClass(?stdClass $data): ?self {
      if (!$data) {
        return null;
      }

      $result = new self();
      $result->setId((int)$data->id);
      $result->setStandaloneSessionResultId((int)$data->standaloneSessionResultId);
      $result->hydratePenaltyFields($data);

      return $result;
    }

    /**
     * @throws Exception
     */
    public static function get(int $id): ?self {
      return self::fromStdClass(StandaloneResultPenaltiesRepository::getById($id));
    }

    /**
     * @return StandaloneResultPenalty[]
     * @throws Exception
     */
    public static function listByResult(int $standaloneSessionResultId): array {
      $results = StandaloneResultPenaltiesRepository::listByResultId($standaloneSessionResultId);

      return array_map(fn($row) => self::fromStdClass($row), $results);
    }

    public function getStandaloneSessionResultId(): int {
      return $this->standaloneSessionResultId;
    }

    public function setStandaloneSessionResultId(int $value): void {
      $this->standaloneSessionResultId = $value;
    }

    /**
     * @throws Exception
     */
    public function save(): self {
      if (!$this->hasId()) {
        $this->setId(StandaloneResultPenaltiesRepository::add($this->toArray()));
      } else {
        StandaloneResultPenaltiesRepository::update($this->getId(), $this->toArray());
      }

      return $this;
    }

    public function toArray(): array {
      return array_merge(
        ['standaloneSessionResultId' => $this->getStandaloneSessionResultId()],
        $this->penaltyFieldsToArray()
      );
    }

    public function toDto(): array {
      return array_merge(
        [
          'id' => $this->getId(),
          'standaloneSessionResultId' => $this->getStandaloneSessionResultId(),
        ],
        $this->penaltyFieldsToArray()
      );
    }
  }
