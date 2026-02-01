<?php

  namespace SLTK\Domain\Abstractions;

  interface Listable {
    /**
     * @return self[]
     */
    public static function list(): array;
  }