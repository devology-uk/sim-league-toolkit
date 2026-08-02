<?php

  namespace SLTK\Domain;

  use Exception;
  use SLTK\Core\Constants;
  use SLTK\Database\Repositories\ChampionshipResultPenaltiesRepository;
  use SLTK\Domain\Abstractions\AggregateRoot;
  use SLTK\Domain\Abstractions\Deletable;
  use SLTK\Domain\Abstractions\ProvidesPersistableArray;
  use SLTK\Domain\Abstractions\Saveable;
  use SLTK\Domain\Traits\HasIdentity;
  use SLTK\Domain\Traits\HasPenaltyFields;
  use stdClass;

  class ChampionshipResultPenalty implements AggregateRoot, Deletable, ProvidesPersistableArray, Saveable {
    use HasIdentity;
    use HasPenaltyFields;

    private int $championshipSessionResultId = Constants::DEFAULT_ID;

    /**
     * @throws Exception
     */
    public static function delete(int $id): void {
      ChampionshipResultPenaltiesRepository::delete($id);
    }

    public static function fromStdClass(?stdClass $data): ?self {
      if (!$data) {
        return null;
      }

      $result = new self();
      $result->setId((int)$data->id);
      $result->setChampionshipSessionResultId((int)$data->championshipSessionResultId);
      $result->hydratePenaltyFields($data);

      return $result;
    }

    /**
     * @throws Exception
     */
    public static function get(int $id): ?self {
      return self::fromStdClass(ChampionshipResultPenaltiesRepository::getById($id));
    }

    /**
     * @return ChampionshipResultPenalty[]
     * @throws Exception
     */
    public static function listByResult(int $championshipSessionResultId): array {
      $results = ChampionshipResultPenaltiesRepository::listByResultId($championshipSessionResultId);

      return array_map(fn($row) => self::fromStdClass($row), $results);
    }

    public function getChampionshipSessionResultId(): int {
      return $this->championshipSessionResultId;
    }

    public function setChampionshipSessionResultId(int $value): void {
      $this->championshipSessionResultId = $value;
    }

    /**
     * @throws Exception
     */
    public function save(): self {
      if (!$this->hasId()) {
        $this->setId(ChampionshipResultPenaltiesRepository::add($this->toArray()));
      } else {
        ChampionshipResultPenaltiesRepository::update($this->getId(), $this->toArray());
      }

      return $this;
    }

    public function toArray(): array {
      return array_merge(
        ['championshipSessionResultId' => $this->getChampionshipSessionResultId()],
        $this->penaltyFieldsToArray()
      );
    }

    public function toDto(): array {
      return array_merge(
        [
          'id' => $this->getId(),
          'championshipSessionResultId' => $this->getChampionshipSessionResultId(),
        ],
        $this->penaltyFieldsToArray()
      );
    }
  }
