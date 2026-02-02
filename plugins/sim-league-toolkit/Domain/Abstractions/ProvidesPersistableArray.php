<?php

  namespace SLTK\Domain\Abstractions;

  interface ProvidesPersistableArray {
    /**
     * @return array{fieldName: string, value: mixed}
     */
    public function toArray(): array;
  }