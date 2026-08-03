<?php

  namespace SLTK\Migration;

  use Exception;
  use SLTK\Database\Repositories\TrackRepository;

  /**
   * Resolves a legacy ACCLT trackId to its SLTK equivalent, by matching track name within a game.
   * ACCLT has no separate track/layout split - each specific configuration (e.g. "Donington GP" vs
   * "Donington National") is its own flat row - so a legacy name may match either an SLTK `Track`
   * directly, or a specific `TrackLayout` under one (the default/primary layout's name typically
   * mirrors its parent track's, so the plain track is matched first and layout only as a fallback).
   *
   * ACCLT and SLTK names also diverge on accents/diacritics and punctuation (e.g. "Nurburgring" vs
   * "Nürburgring", "Spa Francorchamps" vs "Spa-Francorchamps") - both sides are stripped down to bare
   * lowercase alphanumerics before comparing, rather than requiring exact string equality.
   */
  class TrackResolver {
    private array $legacyTrackNamesById;
    private array $sltkLayoutsByGameAndName;
    private array $sltkTrackIdsByGameAndName;

    /**
     * @throws Exception
     */
    public function __construct() {
      $this->legacyTrackNamesById = $this->buildLegacyTrackNamesById();
      $this->sltkTrackIdsByGameAndName = $this->buildSltkTrackIdsByGameAndName();
      $this->sltkLayoutsByGameAndName = $this->buildSltkLayoutsByGameAndName();
    }

    /**
     * @return array{0: int, 1: ?int} [trackId, trackLayoutId]
     * @throws Exception
     */
    public function resolve(int $legacyTrackId, int $gameId): array {
      $legacyName = $this->legacyTrackNamesById[$legacyTrackId] ?? null;
      if ($legacyName === null) {
        throw new Exception(sprintf('legacy track (id %d) not found', $legacyTrackId));
      }

      $key = $gameId . '|' . $this->normalize($legacyName);

      if (isset($this->sltkTrackIdsByGameAndName[$key])) {
        return [$this->sltkTrackIdsByGameAndName[$key], null];
      }

      $layout = $this->sltkLayoutsByGameAndName[$key] ?? null;
      if ($layout !== null) {
        return [(int)$layout->trackId, (int)$layout->id];
      }

      throw new Exception(sprintf('no matching SLTK track found for "%s"', $legacyName));
    }

    /**
     * @throws Exception
     */
    private function buildLegacyTrackNamesById(): array {
      $lookup = [];

      foreach (AccltLegacyDatabase::getTracks() as $legacyTrack) {
        $lookup[(int)$legacyTrack->id] = $legacyTrack->name;
      }

      return $lookup;
    }

    /**
     * @throws Exception
     */
    private function buildSltkLayoutsByGameAndName(): array {
      $lookup = [];

      foreach (TrackRepository::listAllLayouts() as $layout) {
        $lookup[$layout->gameId . '|' . $this->normalize($layout->name)] = $layout;
      }

      return $lookup;
    }

    /**
     * @throws Exception
     */
    private function buildSltkTrackIdsByGameAndName(): array {
      $lookup = [];

      foreach (TrackRepository::list() as $track) {
        $lookup[$track->gameId . '|' . $this->normalize($track->fullName)] = (int)$track->id;
        $lookup[$track->gameId . '|' . $this->normalize($track->shortName)] = (int)$track->id;
      }

      return $lookup;
    }

    private function normalize(string $name): string {
      return preg_replace('/[^a-z0-9]/', '', strtolower(remove_accents($name)));
    }
  }
