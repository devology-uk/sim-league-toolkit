<?php

  namespace SLTK\Core\Enums;

  enum GameKey: string
  {
    case AssettoCorsa = 'AC';
    case AssettoCorsaCompetizione = 'ACC';
    case AssettoCorsaEvo = 'ACE';
    case AutoMobilista2 = 'AMS2';
    case F125 = 'F125';
    case LeMansUltimate = 'LMU';

    public function label(): string
    {
      return match($this)
      {
        self::AssettoCorsa => 'Assetto Corsa',
        self::AssettoCorsaCompetizione => 'Assetto Corsa Competizione',
        self::AssettoCorsaEvo => 'Assetto Corsa Evo',
        self::AutoMobilista2 => 'Automobilista 2',
        self::F125 => 'F1 25',
        self::LeMansUltimate => 'Le Mans Ultimate',
      };
    }

    public static function toArray(): array
    {
      return array_map(
        fn($case) => ['id' => $case->value, 'name' => $case->label()],
        self::cases()
      );
    }
  }
