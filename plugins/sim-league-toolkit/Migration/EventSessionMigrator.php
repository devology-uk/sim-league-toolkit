<?php

  namespace SLTK\Migration;

  use Exception;
  use SLTK\Core\Enums\EventSessionType;
  use SLTK\Core\Enums\GameKey;
  use SLTK\Database\Repositories\EventSessionRepository;
  use SLTK\Domain\EventSession;
  use stdClass;

  /**
   * Migrates a legacy event's practice/qualifying/race sessions into SLTK `EventSession` rows,
   * for either standalone or championship parent events - the underlying repository call is keyed
   * purely by `eventRefId`, so nothing here is event-type-specific.
   */
  class EventSessionMigrator {
    /**
     * AMS2 weather conditions are identified by a hashed 32-bit id rather than a sequential enum;
     * this is ACCLT's `data/ams2-weather.json` lookup, re-expressed as SLTK's select option values.
     */
    private const array AMS2_WEATHER_NAMES_BY_LEGACY_ID = [
      -934211870 => 'Clear',
      296956818 => 'LightCloud',
      888299130 => 'MediumCloud',
      129238383 => 'HeavyCloud',
      -1293634875 => 'Overcast',
      270338437 => 'LightRain',
      1461703858 => 'Rain',
      -1592958063 => 'Storm',
      -2112363295 => 'ThunderStorm',
      2067843977 => 'Foggy',
      -358600329 => 'FogWithRain',
      -754279862 => 'HeavyFog',
      -1604560069 => 'HeavyFogWithRain',
      -1299791789 => 'Hazy',
      1275961519 => 'Random',
    ];

    /**
     * @param stdClass[] $legacySessions
     * @throws Exception
     */
    public function migrate(array $legacySessions, string $legacyGameType, int $eventRefId): void {
      usort($legacySessions, fn(stdClass $a, stdClass $b) => $this->sessionSortKey($a) <=> $this->sessionSortKey($b));

      $typeCounts = [];
      foreach ($legacySessions as $legacySession) {
        $typeCounts[$legacySession->sessionType] = ($typeCounts[$legacySession->sessionType] ?? 0) + 1;
      }

      $typeOccurrences = [];
      foreach ($legacySessions as $index => $legacySession) {
        $sessionType = $this->mapSessionType($legacySession->sessionType);
        $typeOccurrences[$legacySession->sessionType] = ($typeOccurrences[$legacySession->sessionType] ?? 0) + 1;

        $name = EventSessionType::from($sessionType)->label();
        if ($typeCounts[$legacySession->sessionType] > 1) {
          $name .= ' ' . $typeOccurrences[$legacySession->sessionType];
        }

        $session = new EventSession();
        $session->setEventRefId($eventRefId);
        $session->setName($name);
        $session->setSessionType($sessionType);
        $session->setSortOrder($index);
        $session->setAttributes($this->buildSessionAttributes($legacySession, $sessionType, $legacyGameType));

        EventSessionRepository::add($session->toArray());
      }
    }

    private function buildAms2SessionAttributes(stdClass $legacySession, string $sessionType): array {
      $prefix = ucfirst($sessionType);
      $attributes = [$prefix . 'Length' => (int)($legacySession->durationInMinutes ?? 0)];

      if (isset($legacySession->hourOfDay)) {
        $attributes[$prefix . 'DateHour'] = (int)$legacySession->hourOfDay;
      }

      if (isset($legacySession->weatherSlots)) {
        $attributes[$prefix . 'WeatherSlots'] = (int)$legacySession->weatherSlots;
      }

      if (isset($legacySession->weatherProgression)) {
        $attributes[$prefix . 'WeatherProgression'] = (int)$legacySession->weatherProgression;
      }

      for ($slot = 1; $slot <= 4; $slot++) {
        $column = 'weatherSlot' . $slot;
        $weatherName = isset($legacySession->$column)
          ? (self::AMS2_WEATHER_NAMES_BY_LEGACY_ID[(int)$legacySession->$column] ?? null)
          : null;

        if ($weatherName !== null) {
          $attributes[$prefix . 'WeatherSlot' . $slot] = $weatherName;
        }
      }

      return $attributes;
    }

    private function buildSessionAttributes(stdClass $legacySession, string $sessionType, string $legacyGameType): array {
      if ($legacyGameType === GameKey::AutoMobilista2->value) {
        return $this->buildAms2SessionAttributes($legacySession, $sessionType);
      }

      return [
        'hourOfDay' => (int)($legacySession->hourOfDay ?? 0),
        'dayOfWeekend' => (int)($legacySession->dayOfWeekend ?? 0),
        'durationInMinutes' => (int)($legacySession->durationInMinutes ?? 0),
        'timeMultiplier' => (int)($legacySession->timeMultiplier ?? 1),
      ];
    }

    private function mapSessionType(string $legacySessionType): string {
      return match ($legacySessionType) {
        'P' => EventSessionType::Practice->value,
        'Q' => EventSessionType::Qualifying->value,
        default => EventSessionType::Race->value,
      };
    }

    private function sessionSortKey(stdClass $session): array {
      return [
        $session->dayOfWeekend ?? PHP_INT_MAX,
        $session->hourOfDay ?? PHP_INT_MAX,
        (int)$session->id,
      ];
    }
  }
