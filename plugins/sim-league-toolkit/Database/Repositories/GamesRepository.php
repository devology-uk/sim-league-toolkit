<?php

  namespace SLTK\Database\Repositories;

  use SLTK\Database\TableNames;
  use SLTK\Domain\Game;

  /**
   * Provides access to data related to games in the database
   */
  class GamesRepository extends RepositoryBase {

    /**
     * @param int $gameId The id of the target game
     *
     * @return Game A new Game instance, empty if the id is not matched
     */
    public static function getGame(int $gameId): Game {
      $row = self::getRowById(TableNames::GAMES, $gameId);

      return new Game($row);
    }

    /**
     * @param int $gameId The id or the target game
     *
     * @return string The well known gameKey of the game with specified id
     */
    public static function getGameKey(int $gameId): string {
      $tableName = self::prefixedTableName(TableNames::GAMES);

      $query = "SELECT gameKey FROM {$tableName} WHERE id={$gameId};";

      return self::getValue($query);
    }

    /**
     * @param int $gameId The id or the target game
     *
     * @return string The name of the game with specified id
     */
    public static function getName(int $gameId): string {
      $row = self::getRowById(TableNames::GAMES, $gameId);

      return $row->name ?? '';
    }

    /**
     * Gets all games defined in the database
     * @return array
     */
    public static function listAll(): array {
      return self::getResultsFromTable(TableNames::GAMES);
    }
  }