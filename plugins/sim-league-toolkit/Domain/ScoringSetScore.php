<?php

  namespace SLTK\Domain;

  use SLTK\Core\Constants;
  use stdClass;

  class ScoringSetScore extends EntityBase implements TableItem, Validator {
    public final const string SCORE_POINTS_FIELD_NAME = 'sltk_score_points';
    public final const string SCORE_POSITION_FIELD_NAME = 'sltk_score_position';

    private int $points = 0;
    private int $position = 0;
    private int $scoringSetId = 0;

    public function __construct(stdClass $data = null) {
      if($data) {
        $this->id = $data->id;
        $this->scoringSetId = $data->scoringSetId;
        $this->position = $data->position;
        $this->points = $data->points;
      }

    }

    public function getPoints(): int {
      return $this->points ?? 0;
    }

    public function setPoints(int $value): void {
      $this->points = $value;
    }

    public function getPosition(): int {
      return $this->position ?? 0;
    }

    public function setPosition(int $value): void {
      $this->position = $value;
    }

    public function getScoringSetId(): int {
      return $this->scoringSetId ?? 0;
    }

    public function setScoringSetId(int $value): void {
      $this->scoringSetId = $value;
    }

    public function toArray(bool $includeId = true): array {
      $result = [
        'scoringSetId' => $this->scoringSetId,
        'position'     => $this->position,
        'points'       => $this->points,
      ];

      if($includeId && $this->id !== Constants::DEFAULT_ID) {
        $result['id'] = $this->id;
      }

      return $result;
    }

    public function toTableItem(): array {
      return [
        "id"       => $this->id,
        'position' => $this->position,
        'points'   => $this->points,
      ];
    }

    public function toDto(): array {
      return [
        'id' => $this->id,
        'position' => $this->position,
        'points' => $this->points,
      ];
    }

    public function validate(): ValidationResult {
      $result = new ValidationResult();

      if($this->position < 1) {
        $result->addValidationError(self::SCORE_POSITION_FIELD_NAME,
                                    esc_html__('Position must be greater than 0', 'sim-league-toolkit'));
      }

      if($this->position < 1) {
        $result->addValidationError(self::SCORE_POINTS_FIELD_NAME,
                                    esc_html__('Points must be greater than 0', 'sim-league-toolkit'));
      }

      return $result;
    }
  }