<?php

  namespace SLTK\Domain;

  use SLTK\Core\Enums\TrophyAwardType;
  use SLTK\Core\Enums\TrophyScope;

  class ProposedTrophy {
    public function __construct(
      private readonly int $userId,
      private readonly string $memberName,
      private readonly TrophyAwardType $awardType,
      private readonly ?int $eventClassId = null,
      private readonly ?string $className = null,
      private readonly ?int $eventSessionId = null,
      private readonly ?string $sessionName = null,
    ) {
    }

    public function getUserId(): int {
      return $this->userId;
    }

    public function getMemberName(): string {
      return $this->memberName;
    }

    public function getAwardType(): TrophyAwardType {
      return $this->awardType;
    }

    public function getEventClassId(): ?int {
      return $this->eventClassId;
    }

    public function getClassName(): ?string {
      return $this->className;
    }

    public function getEventSessionId(): ?int {
      return $this->eventSessionId;
    }

    public function getSessionName(): ?string {
      return $this->sessionName;
    }

    public function toDto(): array {
      return [
        'userId' => $this->getUserId(),
        'memberName' => $this->getMemberName(),
        'awardType' => $this->getAwardType()->value,
        'eventClassId' => $this->getEventClassId(),
        'className' => $this->getClassName(),
        'eventSessionId' => $this->getEventSessionId(),
        'sessionName' => $this->getSessionName(),
      ];
    }

    public function toTrophy(TrophyScope $scope, int $scopeId): Trophy {
      $trophy = new Trophy();
      $trophy->setMemberId($this->getUserId());
      $trophy->setScope($scope);
      $trophy->setScopeId($scopeId);
      $trophy->setEventSessionId($this->getEventSessionId());
      $trophy->setEventClassId($this->getEventClassId());
      $trophy->setAwardType($this->getAwardType());

      return $trophy;
    }
  }
