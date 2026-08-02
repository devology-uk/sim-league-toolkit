<?php

  namespace SLTK\Domain;

  class TrophyPreviewResult {
    /**
     * @param ProposedTrophy[] $proposedTrophies
     */
    public function __construct(
      private readonly bool $canAward,
      private readonly ?string $reason,
      private readonly array $proposedTrophies,
    ) {
    }

    public function getCanAward(): bool {
      return $this->canAward;
    }

    public function getReason(): ?string {
      return $this->reason;
    }

    /**
     * @return ProposedTrophy[]
     */
    public function getProposedTrophies(): array {
      return $this->proposedTrophies;
    }

    public function toDto(): array {
      return [
        'canAward' => $this->getCanAward(),
        'reason' => $this->getReason(),
        'proposedTrophies' => array_map(fn(ProposedTrophy $p) => $p->toDto(), $this->getProposedTrophies()),
      ];
    }
  }
