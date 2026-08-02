<?php

  namespace SLTK\Domain;

  class TrophyEligibleResult {
    public function __construct(
      private readonly int $userId,
      private readonly string $memberName,
      private readonly ?int $eventClassId,
      private readonly ?string $className,
      private readonly ?int $position,
      private readonly ?int $fastestLapMs,
    ) {
    }

    public static function fromChampionshipResult(ChampionshipSessionResult $result): self {
      return new self(
        $result->getUserId(),
        $result->getMemberName(),
        $result->getEventClassId(),
        $result->getClassName(),
        $result->getPosition(),
        $result->getFastestLapMs(),
      );
    }

    public static function fromStandaloneResult(StandaloneSessionResult $result): self {
      return new self(
        $result->getUserId(),
        $result->getMemberName(),
        $result->getEventClassId(),
        $result->getClassName(),
        $result->getPosition(),
        $result->getFastestLapMs(),
      );
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

    public function getPosition(): ?int {
      return $this->position;
    }

    public function getFastestLapMs(): ?int {
      return $this->fastestLapMs;
    }
  }
