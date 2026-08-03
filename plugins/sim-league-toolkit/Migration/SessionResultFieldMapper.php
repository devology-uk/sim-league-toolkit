<?php

  namespace SLTK\Migration;

  use SLTK\Core\Enums\ResultStatus;
  use stdClass;

  /**
   * Maps one legacy leaderboard row (+ its optional raw result-car/lap data) onto the field values
   * `HasSessionResultFields` expects. Shared by `ChampionshipSessionResultImporter` and
   * `StandaloneSessionResultImporter`, since both target domains use that same trait.
   *
   * Position/totalTimeMs/points are taken from the leaderboard row - ACCLT's already
   * penalty-adjusted, class-scored view. FastestLap/sectors/lapsCompleted come from the raw
   * `acclt_event_result_cars` row where one exists (trusted verbatim, matching how ACCLT itself never
   * re-validates fastest lap against individual laps). `validLapsCount` comes from counting
   * `isValid` laps directly, with no equivalent on the leaderboard.
   *
   * Status: ACCLT never captured a finish status at all. Only DNS is inferrable with any confidence
   * (lapCount = 0); a driver's final lap being one short of `lapCount` is normal race-ending
   * behaviour (their lap was cut short by the checkered flag), not evidence of a DNF - so everyone
   * who set at least one timed lap is treated as Finished. DSQ has no reliable signal in ACCLT at all.
   */
  class SessionResultFieldMapper {
    public static function mapFields(stdClass $leaderBoardRow, ?stdClass $carSummary, ?array $lapValidity): array {
      $lapsCompleted = $carSummary !== null ? (int)$carSummary->lapCount : 0;

      return [
        'position' => isset($leaderBoardRow->position) ? (int)$leaderBoardRow->position : null,
        'totalTimeMs' => !empty($leaderBoardRow->totalTimeMs) ? (int)$leaderBoardRow->totalTimeMs : null,
        'fastestLapMs' => !empty($carSummary->bestLapTimeMs) ? (int)$carSummary->bestLapTimeMs : null,
        'sector1TimeMs' => !empty($carSummary->bestLapSector1Ms) ? (int)$carSummary->bestLapSector1Ms : null,
        'sector2TimeMs' => !empty($carSummary->bestLapSector2Ms) ? (int)$carSummary->bestLapSector2Ms : null,
        'sector3TimeMs' => !empty($carSummary->bestLapSector3Ms) ? (int)$carSummary->bestLapSector3Ms : null,
        'lapsCompleted' => $lapsCompleted,
        'validLapsCount' => $lapValidity !== null ? $lapValidity['valid'] : null,
        'status' => $lapsCompleted === 0 ? ResultStatus::DNS : ResultStatus::Finished,
        'points' => isset($leaderBoardRow->points) ? (int)$leaderBoardRow->points : null,
      ];
    }
  }
