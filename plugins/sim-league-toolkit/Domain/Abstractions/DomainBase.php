<?php

  namespace SLTK\Domain\Abstractions;

  use SLTK\Domain\Abstractions\ValueObject;

  abstract class DomainBase implements ValueObject {

    public static abstract function get(int $id): self|null;

    public static abstract function list(): array;

    public abstract function save(): bool;
  }