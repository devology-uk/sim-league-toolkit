<?php

  namespace SLTK\Domain\Abstractions;

  use stdClass;

  interface ValueObject {
    public static function fromStdClass(?stdClass $data): ?self;

    /**
     * @return array{fieldName: string, value: mixed}
     */
    public function toDto(): array;
  }