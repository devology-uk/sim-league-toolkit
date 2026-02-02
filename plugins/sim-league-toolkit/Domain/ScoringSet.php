<?php

  namespace SLTK\Domain;

  use Exception;
  use SLTK\Core\Constants;
  use SLTK\Database\Repositories\ScoringSetRepository;
  use SLTK\Domain\Abstractions\AggregateRoot;
  use SLTK\Domain\Abstractions\Deletable;
  use SLTK\Domain\Abstractions\Listable;
  use SLTK\Domain\Abstractions\ProvidesPersistableArray;
  use SLTK\Domain\Abstractions\Saveable;
  use SLTK\Domain\Traits\HasIdentity;
  use stdClass;

  class ScoringSet implements AggregateRoot, Deletable, Listable, ProvidesPersistableArray, Saveable {
    use HasIdentity;

    private string $description = '';
    private bool $isBuiltIn = false;
    private bool $isInUse = false;
    private string $name = '';
    private int $pointsForFastestLap = 0;
    private int $pointsForFinishing = 0;
    private int $pointsForPole = 0;

    /**
     * @throws Exception
     */
    public static function delete(int $id): void {
      ScoringSetRepository::delete($id);
    }

    /**
     * @throws Exception
     */
    public static function deleteScore(int $id): void {
      ScoringSetRepository::deleteScore($id);
    }

    public static function fromStdClass(?stdClass $data): ?self {
      if(!$data) {
        return null;
      }

      $result = new self();

      $result->setDescription($data->description ?? '');
      $result->setIsBuiltIn($data->isBuiltIn ?? false);
      $result->setIsInUse($data->isInUse ?? false);
      $result->setName($data->name ?? '');
      $result->setPointsForFastestLap($data->pointsForFastestLap ?? 0);
      $result->setPointsForFinishing($data->pointsForFinishing ?? 0);
      $result->setPointsForPole($data->pointsForPole ?? 0);

      return $result;
    }

    public static function get(int $id): self|null {
      $queryResult = ScoringSetRepository::getById($id);
      return self::fromStdClass($queryResult);
    }

    /**
     * @return ScoringSet[]
     * @throws Exception
     */
    public static function list(): array {
      $queryResults = ScoringSetRepository::list();
      return array_map(function ($item) {
        return self::fromStdClass($item);
      }, $queryResults);
    }

    public function getDescription(): string {
      return trim($this->description ?? '');
    }

    public function setDescription(string $value): void {
      $this->description = trim($value);
    }

    public function getIsBuiltIn(): bool {
      return $this->isBuiltIn ?? false;
    }

    public function setIsBuiltIn(bool $value): void {
      $this->isBuiltIn = $value;
    }

    public function getIsInUse(): bool {
      return $this->isInUse ?? false;
    }

    public function setIsInUse(bool $value): void {
      $this->isInUse = $value;
    }

    public function getName(): string {
      return trim($this->name ?? '');
    }

    public function setName(string $value): void {
      $this->name = trim($value);
    }

    public function getPointsForFastestLap(): int {
      return $this->pointsForFastestLap ?? 0;
    }

    public function setPointsForFastestLap(int $value): void {
      $this->pointsForFastestLap = $value;
    }

    public function getPointsForFinishing(): int {
      return $this->pointsForFinishing ?? 0;
    }

    public function setPointsForFinishing(int $value): void {
      $this->pointsForFinishing = $value;
    }

    public function getPointsForPole(): int {
      return $this->pointsForPole ?? 0;
    }

    public function setPointsForPole(int $value): void {
      $this->pointsForPole = $value;
    }

    /**
     * @return ScoringSetScore[]
     * @throws Exception
     */
    public static function getScores(int $ruleSetId): array {
      $queryResults = ScoringSetRepository::listScores($ruleSetId);

      return array_map(function ($item) {
        return ScoringSetScore::fromStdClass($item);
      }, $queryResults);
    }

    /**
     * @throws Exception
     */
    public function save(): self {
        if ($this->getId() == Constants::DEFAULT_ID) {
          $this->setId(ScoringSetRepository::add($this->toArray()));
        } else {
          ScoringSetRepository::update($this->getId(), $this->toArray());
        }
        return $this;
    }

    public function saveScore(ScoringSetScore $score): bool {
      try {
        $existing = ScoringSetRepository::getScore($this->getId(), $score->getPosition());
        if (isset($existing->id)) {
          ScoringSetRepository::updateScore($existing->id, $score->toArray(false));
        } else {
          $score->setId(ScoringSetRepository::addScore($score->toArray(false)));
        }

        return true;
      } catch (Exception) {
        return false;
      }
    }

    /**
     * @return array{fieldName: string, value: mixed}
     */
    public function toArray(): array {
      return [
        'name' => $this->getName(),
        'description' => $this->getDescription(),
        'pointsForFastestLap' => $this->getPointsForFastestLap(),
        'pointsForFinishing' => $this->getPointsForFinishing(),
        'pointsForPole' => $this->getPointsForPole(),
      ];
    }

    /**
     * @return array{columnName: string, value: mixed}
     */
    public function toDto(): array {
      return [
        'id' => $this->getId(),
        'name' => $this->getName(),
        'description' => $this->getDescription(),
        'isBuiltIn' => $this->getIsBuiltIn(),
        'isInUse' => $this->getIsInUse(),
        'pointsForFastestLap' => $this->getPointsForFastestLap(),
        'pointsForFinishing' => $this->getPointsForFinishing(),
        'pointsForPole' => $this->getPointsForPole(),
      ];
    }
  }