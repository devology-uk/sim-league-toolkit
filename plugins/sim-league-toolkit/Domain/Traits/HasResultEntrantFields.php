<?php

  namespace SLTK\Domain\Traits;

  use SLTK\Core\Constants;
  use stdClass;

  trait HasResultEntrantFields {
    private ?int $eventClassId = null;
    private int $eventSessionId = Constants::DEFAULT_ID;
    private string $memberName = '';
    private int $raceNumber = 0;
    private int $userId = Constants::DEFAULT_ID;

    public function getEventClassId(): ?int {
      return $this->eventClassId;
    }

    private function setEventClassId(?int $value): void {
      $this->eventClassId = $value;
    }

    public function getEventSessionId(): int {
      return $this->eventSessionId;
    }

    public function setEventSessionId(int $value): void {
      $this->eventSessionId = $value;
    }

    public function getMemberName(): string {
      return $this->memberName;
    }

    private function setMemberName(string $value): void {
      $this->memberName = $value;
    }

    public function getRaceNumber(): int {
      return $this->raceNumber;
    }

    private function setRaceNumber(int $value): void {
      $this->raceNumber = $value;
    }

    public function getUserId(): int {
      return $this->userId;
    }

    private function setUserId(int $value): void {
      $this->userId = $value;
    }

    private function hydrateResultEntrantFieldsFromStdClass(stdClass $data): void {
      $this->setEventSessionId((int)$data->eventSessionId);
      $this->setUserId((int)($data->userId ?? Constants::DEFAULT_ID));
      $this->setMemberName($data->memberName ?? '');
      $this->setRaceNumber((int)($data->raceNumber ?? 0));
      $this->setEventClassId(isset($data->eventClassId) && $data->eventClassId > 0 ? (int)$data->eventClassId : null);
    }

    private function resultEntrantFieldsToDto(): array {
      return [
        'eventSessionId' => $this->getEventSessionId(),
        'userId' => $this->getUserId(),
        'memberName' => $this->getMemberName(),
        'raceNumber' => $this->getRaceNumber(),
        'eventClassId' => $this->getEventClassId(),
      ];
    }
  }
