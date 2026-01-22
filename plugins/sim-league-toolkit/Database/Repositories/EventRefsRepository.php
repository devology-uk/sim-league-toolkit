<?php

  namespace SLTK\Database\Repositories;

  use Exception;
  use SLTK\Database\TableNames;

  class EventRefsRepository extends RepositoryBase
  {
    /**
     * @throws Exception
     */
    public static function add(string $eventType): int
    {
      return self::insert(TableNames::EVENT_REFS, [
        'eventType' => $eventType
      ]);
    }

    /**
     * @throws Exception
     */
    public static function getById(int $id): ?\stdClass
    {
      return self::getRowById(TableNames::EVENT_REFS, $id);
    }

    /**
     * @throws Exception
     */
    public static function delete(int $id): void
    {
      self::deleteById(TableNames::EVENT_REFS, $id);
    }
  }
