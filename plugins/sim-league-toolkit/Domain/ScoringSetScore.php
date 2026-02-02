<?php

  namespace SLTK\Domain;

  use SLTK\Domain\Abstractions\ProvidesPersistableArray;
  use SLTK\Domain\Abstractions\ValueObject;
  use SLTK\Domain\Traits\HasIdentity;
  use stdClass;

  class ScoringSetScore implements ValueObject, ProvidesPersistableArray {
    use HasIdentity;

    private int $points = 0;
    private int $position = 0;
    private int $scoringSetId = 0;

    public static function fromStdClass(?stdClass $data): ?self {
      if (!$data) {
        return null;
      }

      $result = new self();
      $result->setId($data->id);
      $result->setScoringSetId($data->scoringSetId);
      $result->setPoints($data->points);

      return $result;
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

    /**
     * @return array{fieldName: string, value: mixed}
     */
    public function toArray(): array {
      return [
        'scoringSetId' => $this->getScoringSetId(),
        'position' => $this->getPosition(),
        'points' => $this->getPoints(),
      ];
    }

    /**
     * @return array{fieldName: string, value: mixed}
     */
    public function toDto(): array {
      return [
        'id' => $this->getId(),
        'position' => $this->getPosition(),
        'points' => $this->getPoints(),
      ];
    }
  }