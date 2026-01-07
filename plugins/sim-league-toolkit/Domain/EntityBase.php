<?php

  namespace SLTK\Domain;

  use SLTK\Core\Constants;
  use stdClass;

  abstract class EntityBase {
    private ?int $id;

    public function __construct(?stdClass $data = null) {
      if (isset($data->id)) {
        $this->id = $data->id;
      }
    }

    public function hasId(): bool {
      return isset($this->id) && $this->id !== Constants::DEFAULT_ID;
    }

    public function getId(): int {
      return $this->id;
    }

    public function setId(int $value): void {
      $this->id = $value;
    }

    public function toArray(): array {
      return get_object_vars($this);
    }
  }