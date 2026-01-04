<?php

  namespace SLTK\Domain;

  use SLTK\Core\Constants;
  use stdClass;

  class ScoringSetScore extends EntityBase {

    private int $points = 0;
    private int $position = 0;
    private int $scoringSetId = 0;

    public function __construct(stdClass $data = null) {
      parent::__construct($data);
      if ($data) {
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
        'scoringSetId' => $this->getScoringSetId(),
        'position' => $this->getPosition(),
        'points' => $this->getPoints(),
      ];

      if ($includeId && $this->getId() !== Constants::DEFAULT_ID) {
        $result['id'] = $this->getId();
      }

      return $result;
    }

    public function toDto(): array {
      return [
        'id' => $this->getId(),
        'position' => $this->getPosition(),
        'points' => $this->getPoints(),
      ];
    }
  }