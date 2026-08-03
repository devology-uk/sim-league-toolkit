<?php

  namespace SLTK\Domain\Traits;

  use SLTK\Core\Enums\ResultStatus;
  use stdClass;

  trait HasSessionResultFields {
    private ?int $position = null;
    private ?int $totalTimeMs = null;
    private ?int $fastestLapMs = null;
    private ?int $sector1TimeMs = null;
    private ?int $sector2TimeMs = null;
    private ?int $sector3TimeMs = null;
    private int $lapsCompleted = 0;
    private ?int $validLapsCount = null;
    private ResultStatus $status = ResultStatus::Finished;
    private ?int $points = null;

    public function getPosition(): ?int {
      return $this->position;
    }

    public function setPosition(?int $value): void {
      $this->position = $value;
    }

    public function getTotalTimeMs(): ?int {
      return $this->totalTimeMs;
    }

    public function setTotalTimeMs(?int $value): void {
      $this->totalTimeMs = $value;
    }

    public function getFastestLapMs(): ?int {
      return $this->fastestLapMs;
    }

    public function setFastestLapMs(?int $value): void {
      $this->fastestLapMs = $value;
    }

    public function getSector1TimeMs(): ?int {
      return $this->sector1TimeMs;
    }

    public function setSector1TimeMs(?int $value): void {
      $this->sector1TimeMs = $value;
    }

    public function getSector2TimeMs(): ?int {
      return $this->sector2TimeMs;
    }

    public function setSector2TimeMs(?int $value): void {
      $this->sector2TimeMs = $value;
    }

    public function getSector3TimeMs(): ?int {
      return $this->sector3TimeMs;
    }

    public function setSector3TimeMs(?int $value): void {
      $this->sector3TimeMs = $value;
    }

    public function getLapsCompleted(): int {
      return $this->lapsCompleted;
    }

    public function setLapsCompleted(int $value): void {
      $this->lapsCompleted = $value;
    }

    public function getValidLapsCount(): ?int {
      return $this->validLapsCount;
    }

    public function setValidLapsCount(?int $value): void {
      $this->validLapsCount = $value;
    }

    public function getStatus(): ResultStatus {
      return $this->status;
    }

    public function setStatus(ResultStatus $value): void {
      $this->status = $value;
    }

    public function getPoints(): ?int {
      return $this->points;
    }

    public function setPoints(?int $value): void {
      $this->points = $value;
    }

    private function hydrateSessionResultFields(stdClass $data): void {
      $this->setPosition(isset($data->position) ? (int)$data->position : null);
      $this->setTotalTimeMs(isset($data->totalTimeMs) ? (int)$data->totalTimeMs : null);
      $this->setFastestLapMs(isset($data->fastestLapMs) ? (int)$data->fastestLapMs : null);
      $this->setSector1TimeMs(isset($data->sector1TimeMs) ? (int)$data->sector1TimeMs : null);
      $this->setSector2TimeMs(isset($data->sector2TimeMs) ? (int)$data->sector2TimeMs : null);
      $this->setSector3TimeMs(isset($data->sector3TimeMs) ? (int)$data->sector3TimeMs : null);
      $this->setLapsCompleted((int)($data->lapsCompleted ?? 0));
      $this->setValidLapsCount(isset($data->validLapsCount) ? (int)$data->validLapsCount : null);
      $this->setStatus(ResultStatus::tryFrom($data->status ?? '') ?? ResultStatus::Finished);
      $this->setPoints(isset($data->points) ? (int)$data->points : null);
    }

    private function sessionResultFieldsToArray(): array {
      return [
        'position' => $this->getPosition(),
        'totalTimeMs' => $this->getTotalTimeMs(),
        'fastestLapMs' => $this->getFastestLapMs(),
        'sector1TimeMs' => $this->getSector1TimeMs(),
        'sector2TimeMs' => $this->getSector2TimeMs(),
        'sector3TimeMs' => $this->getSector3TimeMs(),
        'lapsCompleted' => $this->getLapsCompleted(),
        'validLapsCount' => $this->getValidLapsCount(),
        'status' => $this->getStatus()->value,
        'points' => $this->getPoints(),
      ];
    }
  }
