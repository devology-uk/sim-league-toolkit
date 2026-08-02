<?php

  namespace SLTK\Core\Enums;

  enum TrophyScope: string
  {
    case StandaloneEvent = 'standaloneEvent';
    case ChampionshipEvent = 'championshipEvent';
    case Championship = 'championship';

    public function label(): string
    {
      return match($this)
      {
        self::StandaloneEvent => 'Standalone Event',
        self::ChampionshipEvent => 'Championship Event',
        self::Championship => 'Championship',
      };
    }

    public static function toArray(): array
    {
      return array_map(
        fn($case) => [
          'id' => $case->value,
          'name' => $case->label()
        ],
        self::cases()
      );
    }
  }
