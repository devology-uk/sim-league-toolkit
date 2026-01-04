<?php

  namespace SLTK\Domain;

  use Exception;
  use SLTK\Core\Constants;
  use SLTK\Database\Repositories\ScoringSetRepository;
  use stdClass;

  class ScoringSet extends DomainBase {

    private string $description = '';
    private bool $isBuiltIn = false;
    private bool $isInUse = false;
    private string $name = '';
    private int $pointsForFastestLap = 0;
    private int $pointsForFinishing = 0;
    private int $pointsForPole = 0;

    public function __construct(?stdClass $data = null) {
      parent::__construct($data);
      if ($data != null) {
        $this->description = $data->description ?? '';
        $this->isBuiltIn = $data->isBuiltIn ?? false;
        $this->isInUse = $data->isInUse ?? false;
        $this->name = $data->name ?? '';
        $this->pointsForFastestLap = $data->pointsForFastestLap ?? 0;
        $this->pointsForFinishing = $data->pointsForFinishing ?? 0;
        $this->pointsForPole = $data->pointsForPole ?? 0;
      }
    }

    /**
     * @throws Exception
     */
    public static function delete(int $id): bool {
      ScoringSetRepository::delete($id);

      return true;
    }

    /**
     * @throws Exception
     */
    public static function deleteScore(int $id): bool {
      ScoringSetRepository::deleteScore($id);

      return true;
    }

    public static function get(int $id): ScoringSet|null {
      $result = ScoringSetRepository::getById($id);
      if ($result != null) {
        return new ScoringSet($result);
      }

      return null;
    }

    /**
     * @return ScoringSet[]
     * @throws Exception
     */
    public static function list(): array {
      $results = ScoringSetRepository::list();

      return self::mapScoringSets($results);
    }

    public function getDescription(): string {
      return trim($this->description ?? '');
    }

    public function setDescription(string $value): void {
      $this->description = trim($value);
    }

    public function getIsBuiltIn() {
      return $this->isBuiltIn ?? false;
    }

    public function setIsBuiltIn(bool $value): void {
      $this->isBuiltIn = $value;
    }

    public function getIsInUse() {
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
    public function getScores(): array {
      return self::mapScoringSetScores(ScoringSetRepository::listScores($this->getId()));
    }

    public function save(): bool {
      try {
        if ($this->getId() == Constants::DEFAULT_ID) {
          $this->setId(ScoringSetRepository::add($this->toArray(false)));
        } else {
          ScoringSetRepository::update($this->getId(), $this->toArray(false));
        }
      } catch (Exception) {
        return false;
      }

      return true;
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
    public function toArray(bool $includeId = true): array {
      $result = [
        'name' => $this->getName(),
        'description' => $this->getDescription(),
        'pointsForFastestLap' => $this->getPointsForFastestLap(),
        'pointsForFinishing' => $this->getPointsForFinishing(),
        'pointsForPole' => $this->getPointsForPole(),
      ];

      if ($includeId && $this->getId() != Constants::DEFAULT_ID) {
        $result['id'] = $this->getId();
      }

      return $result;
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

    private static function mapScoringSetScores(array $queryResults): array {
      $results = array();

      foreach ($queryResults as $item) {
        $results[] = new ScoringSetScore($item);
      }

      return $results;
    }

    private static function mapScoringSets(array $queryResults): array {
      $results = array();

      foreach ($queryResults as $item) {
        $results[] = new ScoringSet($item);
      }

      return $results;
    }
  }