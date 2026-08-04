<?php

  namespace SLTK\Domain\ValueObjects;

  use DateTime;

  final class ListingFilter {
    public function __construct(
      public readonly ?DateTime $fromDate = null,
      public readonly ?DateTime $toDate = null,
      public readonly bool $includeInactive = false,
    ) {}
  }
