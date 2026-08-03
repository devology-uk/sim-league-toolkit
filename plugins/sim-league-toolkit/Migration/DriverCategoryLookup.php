<?php

  namespace SLTK\Migration;

  use Exception;
  use SLTK\Domain\DriverCategory;

  /**
   * Resolves ACCLT's free-text driver category names (e.g. 'Bronze') to SLTK driver category ids.
   */
  class DriverCategoryLookup {
    private array $idsByName;

    /**
     * @throws Exception
     */
    public function __construct() {
      $this->idsByName = [];

      foreach (DriverCategory::list() as $category) {
        $this->idsByName[$category->getName()] = $category->getId();
      }
    }

    public function resolve(string $name): ?int {
      return $this->idsByName[$name] ?? null;
    }
  }
