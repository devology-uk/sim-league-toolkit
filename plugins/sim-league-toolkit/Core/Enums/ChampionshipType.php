<?php

  namespace SLTK\Core\Enums;

  enum ChampionshipType: string
  {
    case Standard = 'standard';
    case TrackMaster = 'trackMaster';

    public function label(): string
    {
      return match($this)
      {
        self::Standard => 'Standard',
        self::TrackMaster => 'Track Master',
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