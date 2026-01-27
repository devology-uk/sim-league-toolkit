<?php

  namespace SLTK\Database\Repositories;

  use Exception;
  use SLTK\Database\TableNames;
  use stdClass;

  class ChampionshipEventsRepository extends RepositoryBase
  {
    /**
     * @throws Exception
     */
    public static function getById(int $id): ?stdClass
    {
      $eventsTableName = self::prefixedTableName(TableNames::CHAMPIONSHIP_EVENTS);
      $championshipsTableName = self::prefixedTableName(TableNames::CHAMPIONSHIPS);
      $gamesTableName = self::prefixedTableName(TableNames::GAMES);
      $tracksTableName = self::prefixedTableName(TableNames::TRACKS);
      $layoutsTableName = self::prefixedTableName(TableNames::TRACK_LAYOUTS);

      $query = "SELECT 
                e.*,
                c.name as championship,
                g.name as game,
                t.name as track,
                tl.name as trackLayout
            FROM {$eventsTableName} e
            LEFT JOIN {$championshipsTableName} c ON e.championshipId = c.id
            LEFT JOIN {$gamesTableName} g ON e.gameId = g.id
            LEFT JOIN {$tracksTableName} t ON e.trackId = t.id
            LEFT JOIN {$layoutsTableName} tl ON e.trackLayoutId = tl.id
            WHERE e.id = '{$id}';";

      return self::getRow($query);
    }

    /**
     * @throws Exception
     */
    public static function getByEventRefId(int $eventRefId): ?stdClass
    {
      $eventsTableName = self::prefixedTableName(TableNames::CHAMPIONSHIP_EVENTS);
      $championshipsTableName = self::prefixedTableName(TableNames::CHAMPIONSHIPS);
      $gamesTableName = self::prefixedTableName(TableNames::GAMES);
      $tracksTableName = self::prefixedTableName(TableNames::TRACKS);
      $layoutsTableName = self::prefixedTableName(TableNames::TRACK_LAYOUTS);

      $query = "SELECT 
                e.*,
                c.name as championship,
                g.name as game,
                t.name as track,
                tl.name as trackLayout
            FROM {$eventsTableName} e
            LEFT JOIN {$championshipsTableName} c ON e.championshipId = c.id
            LEFT JOIN {$gamesTableName} g ON e.gameId = g.id
            LEFT JOIN {$tracksTableName} t ON e.trackId = t.id
            LEFT JOIN {$layoutsTableName} tl ON e.trackLayoutId = tl.id
            WHERE e.eventRefId = '{$eventRefId}';";

      return self::getRow($query);
    }

    /**
     * @return stdClass[]
     * @throws Exception
     */
    public static function listAll(): array
    {
      $eventsTableName =  (TableNames::CHAMPIONSHIP_EVENTS);
      $championshipsTableName = self::prefixedTableName(TableNames::CHAMPIONSHIPS);
      $gamesTableName = self::prefixedTableName(TableNames::GAMES);
      $tracksTableName = self::prefixedTableName(TableNames::TRACKS);
      $layoutsTableName = self::prefixedTableName(TableNames::TRACK_LAYOUTS);

      $query = "SELECT 
                e.*,
                c.name as championship,
                g.name as game,
                t.name as track,
                tl.name as trackLayout
            FROM {$eventsTableName} e
            LEFT JOIN {$championshipsTableName} c ON e.championshipId = c.id
            LEFT JOIN {$gamesTableName} g ON e.gameId = g.id
            LEFT JOIN {$tracksTableName} t ON e.trackId = t.id
            LEFT JOIN {$layoutsTableName} tl ON e.trackLayoutId = tl.id
            ORDER BY e.eventDate;";

      return self::getResults($query);
    }

    /**
     * @return stdClass[]
     * @throws Exception
     */
    public static function listByChampionshipId(int $championshipId): array
    {
      $eventsTableName = self::prefixedTableName(TableNames::CHAMPIONSHIP_EVENTS);
      $championshipsTableName = self::prefixedTableName(TableNames::CHAMPIONSHIPS);
      $gamesTableName = self::prefixedTableName(TableNames::GAMES);
      $tracksTableName = self::prefixedTableName(TableNames::TRACKS);
      $layoutsTableName = self::prefixedTableName(TableNames::TRACK_LAYOUTS);

      $query = "SELECT 
                e.*,
                c.name as championship,
                g.name as game,
                t.name as track,
                tl.name as trackLayout
            FROM {$eventsTableName} e
            LEFT JOIN {$championshipsTableName} c ON e.championshipId = c.id
            LEFT JOIN {$gamesTableName} g ON e.gameId = g.id
            LEFT JOIN {$tracksTableName} t ON e.trackId = t.id
            LEFT JOIN {$layoutsTableName} tl ON e.trackLayoutId = tl.id
            WHERE e.championshipId = '{$championshipId}'
            ORDER BY e.round;";

      return self::getResults($query);
    }

    /**
     * @throws Exception
     */
    public static function add(array $data): int
    {
      return self::insert(TableNames::CHAMPIONSHIP_EVENTS, $data);
    }

    /**
     * @throws Exception
     */
    public static function update(int $id, array $data): void
    {
      self::updateById(TableNames::CHAMPIONSHIP_EVENTS, $id, $data);
    }

    /**
     * @throws Exception
     */
    public static function delete(int $id): void
    {
      self::deleteById(TableNames::CHAMPIONSHIP_EVENTS, $id);
    }
  }