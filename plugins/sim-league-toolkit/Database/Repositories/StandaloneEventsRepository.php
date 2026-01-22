<?php

  namespace SLTK\Database\Repositories;

  use Exception;
  use SLTK\Database\TableNames;

  class StandaloneEventsRepository extends RepositoryBase
  {
    /**
     * @throws Exception
     */
    public static function getById(int $id): ?\stdClass
    {
      $events = TableNames::prefixed(TableNames::STANDALONE_EVENTS);
      $games = TableNames::prefixed(TableNames::GAMES);
      $tracks = TableNames::prefixed(TableNames::TRACKS);
      $layouts = TableNames::prefixed(TableNames::TRACK_LAYOUTS);
      $scoringSets = TableNames::prefixed(TableNames::SCORING_SETS);
      $ruleSets = TableNames::prefixed(TableNames::RULE_SETS);

      $query = "SELECT 
                e.*,
                g.name as game,
                t.name as track,
                tl.name as trackLayout,
                ss.name as scoringSet,
                rs.name as ruleSet
            FROM {$events} e
            LEFT JOIN {$games} g ON e.gameId = g.id
            LEFT JOIN {$tracks} t ON e.trackId = t.id
            LEFT JOIN {$layouts} tl ON e.trackLayoutId = tl.id
            LEFT JOIN {$scoringSets} ss ON e.scoringSetId = ss.id
            LEFT JOIN {$ruleSets} rs ON e.ruleSetId = rs.id
            WHERE e.id = '{$id}';";

      return self::getRow($query);
    }

    /**
     * @throws Exception
     */
    public static function getByEventRefId(int $eventRefId): ?\stdClass
    {
      $events = TableNames::prefixed(TableNames::STANDALONE_EVENTS);
      $games = TableNames::prefixed(TableNames::GAMES);
      $tracks = TableNames::prefixed(TableNames::TRACKS);
      $layouts = TableNames::prefixed(TableNames::TRACK_LAYOUTS);
      $scoringSets = TableNames::prefixed(TableNames::SCORING_SETS);
      $ruleSets = TableNames::prefixed(TableNames::RULE_SETS);

      $query = "SELECT 
                e.*,
                g.name as game,
                t.name as track,
                tl.name as trackLayout,
                ss.name as scoringSet,
                rs.name as ruleSet
            FROM {$events} e
            LEFT JOIN {$games} g ON e.gameId = g.id
            LEFT JOIN {$tracks} t ON e.trackId = t.id
            LEFT JOIN {$layouts} tl ON e.trackLayoutId = tl.id
            LEFT JOIN {$scoringSets} ss ON e.scoringSetId = ss.id
            LEFT JOIN {$ruleSets} rs ON e.ruleSetId = rs.id
            WHERE e.eventRefId = '{$eventRefId}';";

      return self::getRow($query);
    }

    /**
     * @return \stdClass[]
     * @throws Exception
     */
    public static function listAll(): array
    {
      $events = TableNames::prefixed(TableNames::STANDALONE_EVENTS);
      $games = TableNames::prefixed(TableNames::GAMES);
      $tracks = TableNames::prefixed(TableNames::TRACKS);
      $layouts = TableNames::prefixed(TableNames::TRACK_LAYOUTS);
      $scoringSets = TableNames::prefixed(TableNames::SCORING_SETS);
      $ruleSets = TableNames::prefixed(TableNames::RULE_SETS);

      $query = "SELECT 
                e.*,
                g.name as game,
                t.name as track,
                tl.name as trackLayout,
                ss.name as scoringSet,
                rs.name as ruleSet
            FROM {$events} e
            LEFT JOIN {$games} g ON e.gameId = g.id
            LEFT JOIN {$tracks} t ON e.trackId = t.id
            LEFT JOIN {$layouts} tl ON e.trackLayoutId = tl.id
            LEFT JOIN {$scoringSets} ss ON e.scoringSetId = ss.id
            LEFT JOIN {$ruleSets} rs ON e.ruleSetId = rs.id
            ORDER BY e.eventDate ASC;";

      return self::getResults($query);
    }

    /**
     * @throws Exception
     */
    public static function add(array $data): int
    {
      return self::insert(TableNames::STANDALONE_EVENTS, $data);
    }

    /**
     * @throws Exception
     */
    public static function update(int $id, array $data): void
    {
      self::updateById(TableNames::STANDALONE_EVENTS, $id, $data);
    }

    /**
     * @throws Exception
     */
    public static function delete(int $id): void
    {
      self::deleteById(TableNames::STANDALONE_EVENTS, $id);
    }
  }