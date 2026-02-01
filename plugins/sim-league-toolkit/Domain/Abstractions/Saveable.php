<?php

  namespace SLTK\Domain\Abstractions;

  interface Saveable {
    public function save(): self;
  }