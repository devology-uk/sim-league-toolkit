<?php

  namespace SLTK\Database\Repositories;

  use Exception;
  use SLTK\Database\TableNames;
  use stdClass;

  class ChampionshipRepository extends RepositoryBase {
    /**
     * @throws Exception
     */
    public static function add(array $championship): int {
      return self::insert(TableNames::CHAMPIONSHIPS, $championship);
    }

    /**
     * @throws Exception
     */
    public static function addChampionshipClass(array $mapping): void {
      self::insertWithoutReturn(TableNames::CHAMPIONSHIP_EVENT_CLASSES, $mapping);
    }

    /**
     * @throws Exception
     */
    public static function delete(int $id): void {
      self::deleteById(TableNames::CHAMPIONSHIPS, $id);
    }

    /**
     * @throws Exception
     */
    public static function deleteClass(int $championshipId, int $eventClassId): void {
      $championshipEventClassesTableName = self::prefixedTableName(TableNames::CHAMPIONSHIP_EVENT_CLASSES);

      $query = "DELETE FROM $championshipEventClassesTableName
                WHERE championshipId = $championshipId
                AND eventClassId = $eventClassId";

      self::execute($query);
    }

    /**
     * @throws Exception
     */
    public static function getById(int $id): stdClass {
      $championshipsTableName = self::prefixedTableName(TableNames::CHAMPIONSHIPS);
      $gamesTableName = self::prefixedTableName(TableNames::GAMES);
      $platformsTableName = self::prefixedTableName(TableNames::PLATFORMS);
      $tracksTableName = self::prefixedTableName(TableNames::TRACKS);
      $trackLayoutsTableName = self::prefixedTableName(TableNames::TRACK_LAYOUTS);

      $query = "SELECT c.*, p.name as platform, g.name as game, t.shortName as trackMasterTrack, tl.name as trackMasterTrackLayout 
                FROM $championshipsTableName c
                INNER JOIN $platformsTableName p
                ON c.platformId = p.id
                INNER JOIN $gamesTableName g
                ON c.gameId = g.id
                LEFT OUTER JOIN $tracksTableName t
                ON c.trackMasterTrackId = t.id
                LEFT OUTER JOIN $trackLayoutsTableName tl
                ON c.trackMasterTrackLayoutId = tl.id
                WHERE c.id = $id";

      return self::getRow($query);
    }

    /**
     * @throws Exception
     */
    public static function listAll(): array {
      $championshipsTableName = self::prefixedTableName(TableNames::CHAMPIONSHIPS);
      $gamesTableName = self::prefixedTableName(TableNames::GAMES);
      $platformsTableName = self::prefixedTableName(TableNames::PLATFORMS);
      $tracksTableName = self::prefixedTableName(TableNames::TRACKS);
      $trackLayoutsTableName = self::prefixedTableName(TableNames::TRACK_LAYOUTS);

      $query = "SELECT c.*, p.name as platform, g.name as game, t.shortName as trackMasterTrack, tl.name as trackMasterTrackLayout 
                FROM $championshipsTableName c
                INNER JOIN $platformsTableName p
                ON c.platformId = p.id
                INNER JOIN $gamesTableName g
                ON c.gameId = g.id
                LEFT OUTER JOIN $tracksTableName t
                ON c.trackMasterTrackId = t.id
                LEFT OUTER JOIN $trackLayoutsTableName tl
                ON c.trackMasterTrackLayoutId = tl.id
                ORDER BY c.isActive DESC, startDate;";

      return self::getResults($query);
    }

    public static function update(int $id, array $updates): void {
      self::updateById(TableNames::CHAMPIONSHIPS, $id, $updates);
    }
  }