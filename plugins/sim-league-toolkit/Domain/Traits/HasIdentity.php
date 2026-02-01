<?php

  namespace SLTK\Domain\Traits;

  use SLTK\Core\Constants;

  trait HasIdentity {
    private int $id = Constants::DEFAULT_ID;

    public function getId(): int {
      return $this->id;
    }

    public function setId(int $id): void {
      $this->id = $id;
    }

    public function hasId(): bool {
      return isset($this->id) && $this->id != Constants::DEFAULT_ID;
    }
  }

