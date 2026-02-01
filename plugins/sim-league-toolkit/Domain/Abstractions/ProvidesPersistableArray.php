<?php

  namespace SLTK\Domain\Abstractions;

  interface ProvidesPersistableArray {
    public function toArray(): array;
  }