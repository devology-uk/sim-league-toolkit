<?php

  namespace SLTK\Domain;

  use SLTK\Core\Constants;

  /**
   * Base class for domain objects
   */
  abstract class DomainBase {
    public int $id = Constants::DEFAULT_ID;

    /**
     * Retrieves and instance of the domain object from the database
     *
     * @param int $id The id of the domain object to get
     *
     * @return object|null
     */
    public static abstract function get(int $id): object|null;

    /**
     * Retrieves all instances of the domain object from the database
     *
     * @return array
     */
    public static abstract function list(): array;

    /**
     * Saves the domain object to the database
     *
     * @return bool Indicated whether the save was successful or not
     */
    public abstract function save(): bool;

    private function toArray(): array {
      return get_object_vars($this);
    }
  }