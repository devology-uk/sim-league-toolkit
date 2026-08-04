<?php

  namespace SLTK\Blocks\Render;

  use DateTime;
  use SLTK\Domain\ValueObjects\ListingFilter;

  trait ParsesListingFilterAttributes {
    private function filterFromAttributes(array $attributes): ListingFilter {
      $includeInactive = (bool)($attributes['includeInactive'] ?? false);
      $showAll = (bool)($attributes['showAll'] ?? true);

      if ($showAll) {
        return new ListingFilter(includeInactive: $includeInactive);
      }

      $hasStartLimit = (bool)($attributes['hasStartLimit'] ?? true);
      $hasEndLimit = (bool)($attributes['hasEndLimit'] ?? false);

      return new ListingFilter(
        fromDate: $hasStartLimit ? $this->dateFromOffset((int)($attributes['startOffsetDays'] ?? 0)) : null,
        toDate: $hasEndLimit ? $this->dateFromOffset((int)($attributes['endOffsetDays'] ?? 0)) : null,
        includeInactive: $includeInactive,
      );
    }

    private function dateFromOffset(int $offsetDays): DateTime {
      return (new DateTime())->modify(sprintf('%+d days', $offsetDays));
    }
  }
