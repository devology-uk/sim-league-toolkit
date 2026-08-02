<?php

  namespace SLTK\Domain;

  use Exception;
  use SLTK\Core\Enums\EventSessionType;

  class ChampionshipStandingsCalculator {
    /**
     * @return StandingLine[] sorted by total points, descending
     * @throws Exception
     */
    public static function calculate(int $championshipId): array {
      $totals = [];

      foreach (Championship::listEvents($championshipId) as $event) {
        if (!$event->hasEventRef()) {
          continue;
        }

        $raceSessions = array_filter(
          ChampionshipEvent::listSessions($event->getEventRefId()),
          fn(EventSession $s) => $s->getSessionType() === EventSessionType::Race->value
        );

        foreach ($raceSessions as $session) {
          foreach (ChampionshipSessionResult::listByEventSession($session->getId()) as $result) {
            $entryId = $result->getChampionshipEntryId();

            if (!isset($totals[$entryId])) {
              $totals[$entryId] = [
                'userId' => $result->getUserId(),
                'memberName' => $result->getMemberName(),
                'eventClassId' => $result->getEventClassId(),
                'className' => $result->getClassName(),
                'points' => 0,
              ];
            }

            $totals[$entryId]['points'] += $result->getPoints() ?? 0;
          }
        }
      }

      $standings = array_map(
        fn($t) => new StandingLine($t['userId'], $t['memberName'], $t['eventClassId'], $t['className'], $t['points']),
        array_values($totals)
      );

      usort($standings, fn(StandingLine $a, StandingLine $b) => $b->getTotalPoints() <=> $a->getTotalPoints());

      return $standings;
    }
  }
