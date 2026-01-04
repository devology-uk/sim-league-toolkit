<?php

  namespace SLTK\Domain;

  use SLTK\Core\Constants;
  use stdClass;

  abstract class EntityBase {
    private int $id = Constants::DEFAULT_ID;

    public function __construct(?stdClass $data = null) {
      if (isset($data->id)) {
        $this->id = $data->id;
      }
    }

    public function getId(): int {
      return $this->id ?? 0;
    }

    public function setId(int $value): void {
      $this->id = $value;
    }

    public function toArray(): array {
      return get_object_vars($this);
    }
  }