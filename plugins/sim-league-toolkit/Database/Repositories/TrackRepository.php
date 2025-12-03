<?php

  namespace SLTK\Database\Repositories;

  use Exception;
  use SLTK\Database\TableNames;

  class TrackRepository extends RepositoryBase {
    /**
     * @throws Exception
     */
    public static function listForGame(int $gameId): array {
      return self::getResultsFromTable(TableNames::TRACKS, "gameId = $gameId", 'shortName');
    }
  }