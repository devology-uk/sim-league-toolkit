<?php

  namespace SLTK\Domain;

  abstract class DomainBase extends EntityBase {
    public static abstract function get(int $id): object|null;

    public static abstract function list(): array;

    public abstract function save(): bool;

    public function toArray(): array {
      return get_object_vars($this);
    }
  }