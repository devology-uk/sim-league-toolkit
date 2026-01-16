<?php

  namespace SLTK\Database\Repositories;

  use Exception;
  use SLTK\Database\TableNames;
  use stdClass;

  class ChampionshipEventRepository extends RepositoryBase {

    /**
     * @throws Exception
     */
    public static function add(array $championshipEvent): int {
      return self::insert(TableNames::CHAMPIONSHIP_EVENTS, $championshipEvent);
    }

    /**
     * @throws Exception
     */
    public static function delete(int $id): void {
      self::deleteById(TableNames::CHAMPIONSHIP_EVENTS, $id);
    }

    /**
     * @throws Exception
     */
    public static function getById(int $id): stdClass {
      $championshipEventsTableName = self::prefixedTableName(TableNames::CHAMPIONSHIP_EVENTS);
      $championshipsTableName = self::prefixedTableName(TableNames::CHAMPIONSHIPS);
      $tracksTableName = self::prefixedTableName(TableNames::GAMES);
      $trackLayoutsTableName = self::prefixedTableName(TableNames::PLATFORMS);
      $ruleSetsTableName = self::prefixedTableName(TableNames::RULE_SETS);

      $query = "SELECT ce.*, c.name as championShip, rs.name as ruleSet, t.name as track, tl.name as trackLayout 
                FROM $championshipEventsTableName ce
                INNER JOIN $championshipsTableName c
                ON ce.championshipId = c.id            
                INNER JOIN $tracksTableName t
                ON ce.trackId = t.id
                LEFT OUTER JOIN $trackLayoutsTableName tl
                ON ce.trackLayoutId = tl.id
                LEFT OUTER JOIN $ruleSetsTableName rs
                ON ce.ruleSetId = rs.id
                WHERE ce.id = $id
                ORDER BY ce.isActive DESC, ce.startDate;";

      return self::getRow($query);
    }

    /**
     * @throws Exception
     */
    public static function list(): array {
      $championshipEventsTableName = self::prefixedTableName(TableNames::CHAMPIONSHIP_EVENTS);
      $championshipsTableName = self::prefixedTableName(TableNames::CHAMPIONSHIPS);
      $tracksTableName = self::prefixedTableName(TableNames::GAMES);
      $trackLayoutsTableName = self::prefixedTableName(TableNames::PLATFORMS);
      $ruleSetsTableName = self::prefixedTableName(TableNames::RULE_SETS);

      $query = "SELECT ce.*, c.name as championShip, rs.name as ruleSet, t.name as track, tl.name as trackLayout 
                FROM $championshipEventsTableName ce
                INNER JOIN $championshipsTableName c
                ON ce.championshipId = c.id            
                INNER JOIN $tracksTableName t
                ON ce.trackId = t.id
                LEFT OUTER JOIN $trackLayoutsTableName tl
                ON ce.trackLayoutId = tl.id
                LEFT OUTER JOIN $ruleSetsTableName rs
                ON ce.ruleSetId = rs.id
                ORDER BY ce.isActive DESC, ce.startDate;";

      return self::getResults($query);
    }

    public static function update(int $id, array $updates): void {
      self::updateById(TableNames::CHAMPIONSHIP_EVENTS, $id, $updates);
    }
  }