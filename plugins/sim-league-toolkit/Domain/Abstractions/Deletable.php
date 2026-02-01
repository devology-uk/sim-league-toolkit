<?php

  namespace SLTK\Domain\Abstractions;

  interface Deletable {
    public static function delete(int $id): void;
  }

