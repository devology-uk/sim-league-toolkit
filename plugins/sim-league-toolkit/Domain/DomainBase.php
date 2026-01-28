<?php

  namespace SLTK\Domain;

  use stdClass;

  abstract class DomainBase extends EntityBase {
    public function __construct(?stdClass $data = null) {
      parent::__construct($data);
    }

    public static abstract function get(int $id): object|null;

    public static abstract function list(): array;

    public abstract function save(): void;
  }