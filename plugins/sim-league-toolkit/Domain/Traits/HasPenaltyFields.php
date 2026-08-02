<?php

  namespace SLTK\Domain\Traits;

  use stdClass;

  trait HasPenaltyFields {
    private string $reason = '';
    private ?int $penaltySeconds = null;

    public function getReason(): string {
      return $this->reason;
    }

    public function setReason(string $value): void {
      $this->reason = trim($value);
    }

    public function getPenaltySeconds(): ?int {
      return $this->penaltySeconds;
    }

    public function setPenaltySeconds(?int $value): void {
      $this->penaltySeconds = $value;
    }

    private function hydratePenaltyFields(stdClass $data): void {
      $this->setReason($data->reason ?? '');
      $this->setPenaltySeconds(isset($data->penaltySeconds) ? (int)$data->penaltySeconds : null);
    }

    private function penaltyFieldsToArray(): array {
      return [
        'reason' => $this->getReason(),
        'penaltySeconds' => $this->getPenaltySeconds(),
      ];
    }
  }
