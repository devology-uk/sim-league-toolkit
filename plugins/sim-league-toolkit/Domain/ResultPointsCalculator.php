<?php

  namespace SLTK\Domain;

  use Exception;

  class ResultPointsCalculator {
    /**
     * @throws Exception
     */
    public static function calculate(?int $scoringSetId, ?int $position): ?int {
      if ($position === null || $scoringSetId === null || $scoringSetId <= 0) {
        return null;
      }

      $scores = ScoringSet::listScores($scoringSetId);

      foreach ($scores as $score) {
        if ($score->getPosition() === $position) {
          return $score->getPoints();
        }
      }

      return null;
    }
  }
