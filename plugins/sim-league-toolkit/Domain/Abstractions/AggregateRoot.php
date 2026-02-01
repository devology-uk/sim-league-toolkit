<?php

  namespace SLTK\Domain\Abstractions;

  interface AggregateRoot extends ValueObject{
    public static function get(int $id): ?self;
  }