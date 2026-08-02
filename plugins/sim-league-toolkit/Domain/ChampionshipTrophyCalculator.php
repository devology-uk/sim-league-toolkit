<?php

  namespace SLTK\Domain;

  use SLTK\Core\Enums\TrophyAwardType;

  class ChampionshipTrophyCalculator {
    /**
     * @param StandingLine[] $standings sorted descending by total points
     * @return ProposedTrophy[]
     */
    public static function calculate(array $standings): array {
      $trophies = self::podium($standings);

      $classIds = array_unique(array_filter(array_map(fn(StandingLine $s) => $s->getEventClassId(), $standings)));

      if (count($classIds) > 1) {
        foreach ($classIds as $classId) {
          $classStandings = array_values(array_filter($standings, fn(StandingLine $s) => $s->getEventClassId() === $classId));

          array_push($trophies, ...self::podium($classStandings));
        }
      }

      return $trophies;
    }

    /**
     * @param StandingLine[] $standings
     * @return ProposedTrophy[]
     */
    private static function podium(array $standings): array {
      $trophies = [];

      foreach (array_slice($standings, 0, 3) as $index => $standing) {
        $awardType = TrophyAwardType::fromPodiumPosition($index + 1);

        if ($awardType === null) {
          continue;
        }

        $trophies[] = new ProposedTrophy(
          $standing->getUserId(),
          $standing->getMemberName(),
          $awardType,
          $standing->getEventClassId(),
          $standing->getClassName(),
        );
      }

      return $trophies;
    }
  }
