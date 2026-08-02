<?php

  namespace SLTK\Domain;

  class StandingLine {
    public function __construct(
      private readonly int $userId,
      private readonly string $memberName,
      private readonly ?int $eventClassId,
      private readonly ?string $className,
      private readonly int $totalPoints,
    ) {
    }

    public function getUserId(): int {
      return $this->userId;
    }

    public function getMemberName(): string {
      return $this->memberName;
    }

    public function getEventClassId(): ?int {
      return $this->eventClassId;
    }

    public function getClassName(): ?string {
      return $this->className;
    }

    public function getTotalPoints(): int {
      return $this->totalPoints;
    }
  }
