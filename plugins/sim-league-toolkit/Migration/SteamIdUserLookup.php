<?php

  namespace SLTK\Migration;

  use Exception;

  /**
   * Resolves ACCLT's leaderboard `playerId` (always `'S' . steamId64` in this league's data - 100%
   * of leaderboard rows use the Steam prefix) back to the SLTK/WordPress userId, via
   * `acclt_user_profile` (whose `userId` is already a direct `wp_users.ID`, no separate id space).
   */
  class SteamIdUserLookup {
    private array $userIdBySteamPlayerId;

    /**
     * @throws Exception
     */
    public function __construct() {
      $this->userIdBySteamPlayerId = [];

      foreach (AccltLegacyDatabase::getUserProfiles() as $profile) {
        if (empty($profile->steamId)) {
          continue;
        }

        $this->userIdBySteamPlayerId['S' . $profile->steamId] = (int)$profile->userId;
      }
    }

    public function resolve(string $playerId): ?int {
      return $this->userIdBySteamPlayerId[$playerId] ?? null;
    }
  }
