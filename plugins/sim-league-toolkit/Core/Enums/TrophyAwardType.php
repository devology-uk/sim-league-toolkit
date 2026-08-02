<?php

  namespace SLTK\Core\Enums;

  enum TrophyAwardType: string
  {
    case First = 'first';
    case Second = 'second';
    case Third = 'third';
    case Pole = 'pole';
    case FastestLap = 'fastestLap';

    public function label(): string
    {
      return match($this)
      {
        self::First => '1st Place',
        self::Second => '2nd Place',
        self::Third => '3rd Place',
        self::Pole => 'Pole Position',
        self::FastestLap => 'Fastest Lap',
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

    public static function fromPodiumPosition(int $position): ?self
    {
      return match($position)
      {
        1 => self::First,
        2 => self::Second,
        3 => self::Third,
        default => null,
      };
    }
  }
