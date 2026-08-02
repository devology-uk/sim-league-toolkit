<?php

  namespace SLTK\Domain;

  use SLTK\Core\Enums\TrophyAwardType;

  class EventTrophyCalculator {
    /**
     * @param TrophyEligibleResult[] $raceResults
     * @param TrophyEligibleResult[] $poleSessionResults
     * @return ProposedTrophy[]
     */
    public static function calculate(EventSession $raceSession, array $raceResults, array $poleSessionResults): array {
      $trophies = [];

      $finishers = array_values(array_filter($raceResults, fn(TrophyEligibleResult $r) => $r->getPosition() !== null));
      usort($finishers, fn(TrophyEligibleResult $a, TrophyEligibleResult $b) => $a->getPosition() <=> $b->getPosition());

      foreach (self::podium($finishers) as $trophy) {
        $trophies[] = self::tag($trophy, $raceSession);
      }

      $classIds = array_unique(array_filter(array_map(fn(TrophyEligibleResult $r) => $r->getEventClassId(), $finishers)));

      if (count($classIds) > 1) {
        foreach ($classIds as $classId) {
          $classFinishers = array_values(array_filter($finishers, fn(TrophyEligibleResult $r) => $r->getEventClassId() === $classId));

          foreach (self::podium($classFinishers) as $trophy) {
            $trophies[] = self::tag($trophy, $raceSession);
          }
        }
      }

      $fastestLap = self::fastestLap($raceResults);
      if ($fastestLap !== null) {
        $trophies[] = self::tag(
          new ProposedTrophy($fastestLap->getUserId(), $fastestLap->getMemberName(), TrophyAwardType::FastestLap),
          $raceSession
        );
      }

      $pole = self::pole($poleSessionResults);
      if ($pole !== null) {
        $trophies[] = self::tag(
          new ProposedTrophy($pole->getUserId(), $pole->getMemberName(), TrophyAwardType::Pole),
          $raceSession
        );
      }

      return $trophies;
    }

    /**
     * @param TrophyEligibleResult[] $sortedFinishers
     * @return ProposedTrophy[]
     */
    private static function podium(array $sortedFinishers): array {
      $trophies = [];

      foreach (array_slice($sortedFinishers, 0, 3) as $index => $result) {
        $awardType = TrophyAwardType::fromPodiumPosition($index + 1);

        if ($awardType === null) {
          continue;
        }

        $trophies[] = new ProposedTrophy(
          $result->getUserId(),
          $result->getMemberName(),
          $awardType,
          $result->getEventClassId(),
          $result->getClassName(),
        );
      }

      return $trophies;
    }

    /**
     * @param TrophyEligibleResult[] $results
     */
    private static function fastestLap(array $results): ?TrophyEligibleResult {
      $best = null;

      foreach ($results as $result) {
        if ($result->getFastestLapMs() === null) {
          continue;
        }

        if ($best === null || $result->getFastestLapMs() < $best->getFastestLapMs()) {
          $best = $result;
        }
      }

      return $best;
    }

    /**
     * @param TrophyEligibleResult[] $poleSessionResults
     */
    private static function pole(array $poleSessionResults): ?TrophyEligibleResult {
      foreach ($poleSessionResults as $result) {
        if ($result->getPosition() === 1) {
          return $result;
        }
      }

      return null;
    }

    private static function tag(ProposedTrophy $trophy, EventSession $raceSession): ProposedTrophy {
      return new ProposedTrophy(
        $trophy->getUserId(),
        $trophy->getMemberName(),
        $trophy->getAwardType(),
        $trophy->getEventClassId(),
        $trophy->getClassName(),
        $raceSession->getId(),
        $raceSession->getName(),
      );
    }
  }
