<?php

  namespace SLTK\Domain\Abstractions;

  use stdClass;

  interface ValueObject {
    public static function fromStdClass(?stdClass $data): ?self;

    public function toDto(): array;
  }