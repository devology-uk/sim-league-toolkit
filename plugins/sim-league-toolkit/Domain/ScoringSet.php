<?php

  namespace SLTK\Domain;

  use Exception;
  use SLTK\Core\CommonFieldNames;
  use SLTK\Core\Constants;
  use SLTK\Database\Repositories\ScoringSetRepository;
  use stdClass;

  class ScoringSet extends DomainBase implements Validator {
    public final const string DESCRIPTION_FIELD_NAME = CommonFieldNames::DESCRIPTION;
    public final const string NAME_FIELD_NAME = CommonFieldNames::NAME;
    public final const string POINTS_FOR_FASTEST_LAP_FIELD_NAME = 'sltk_fastest_lap_points';
    public final const string POINTS_FOR_FINISHING_FIELD_NAME = 'sltk_finish_points';
    public final const string POINTS_FOR_POLE_FIELD_NAME = 'sltk_pole_points';

    private string $description = '';
    private string $name = '';
    private int $pointsForFastestLap = 0;
    private int $pointsForFinishing = 0;
    private int $pointsForPole = 0;

    public function __construct(stdClass $data = null) {
      if ($data != null) {
        $this->id = $data->id;
        $this->description = $data->description ?? '';
        $this->name = $data->name ?? '';
        $this->pointsForFastestLap = $data->pointsForFastestLap;
        $this->pointsForFinishing = $data->pointsForFinishing;
        $this->pointsForPole = $data->pointsForPole;
      }
    }

    public static function get(int $id): ScoringSet|null {
      $result = ScoringSetRepository::getById($id);
      if ($result != null) {
        return new ScoringSet($result);
      }

      return null;
    }

    /**
     * @return ScoringSet[] Collection of all scoring sets
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
      return self::mapScoringSetScores(ScoringSetRepository::listScores($this->id));
    }

    public function save(): bool {
      try {
        if ($this->id == Constants::DEFAULT_ID) {
          $this->id = ScoringSetRepository::add($this->toArray(false));
        } else {
          ScoringSetRepository::update($this->id, $this->toArray(false));
        }
      } catch (Exception) {
        return false;
      }

      return true;
    }

    public function saveScore(ScoringSetScore $score): bool {
      try {
        $existing = ScoringSetRepository::getScore($this->id, $score->getPosition());
        if (isset($existing->id)) {
          ScoringSetRepository::updateScore($existing->id, $score->toArray(false));
        } else {
          $score->id = ScoringSetRepository::addScore($score->toArray(false));
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
        'name' => $this->name,
        'description' => $this->description,
        'pointsForFastestLap' => $this->pointsForFastestLap,
        'pointsForFinishing' => $this->pointsForFinishing,
        'pointsForPole' => $this->pointsForPole,
      ];

      if ($includeId && $this->id != Constants::DEFAULT_ID) {
        $result['id'] = $this->id;
      }

      return $result;
    }

    /**
     * @return array{columnName: string, value: mixed}
     */
    public function toTableItem(): array {
      return [
        'id' => $this->id,
        'name' => $this->name,
        'pointsForFastestLap' => $this->pointsForFastestLap,
        'pointsForFinishing' => $this->pointsForFinishing,
        'pointsForPole' => $this->pointsForPole,
      ];
    }

    public function validate(): ValidationResult {
      $result = new ValidationResult();

      if (empty($this->name)) {
        $result->addValidationError(self::NAME_FIELD_NAME, esc_html__('Name is required.', 'sim-league-toolkit'));
      }

      if (empty($this->description)) {
        $result->addValidationError(self::DESCRIPTION_FIELD_NAME,
          esc_html__('Description is required.', 'sim-league-toolkit'));
      }

      return $result;
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