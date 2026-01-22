<?php

  namespace SLTK\Core\Enums;

  enum EventSessionType: string
  {
    case Practice = 'practice';
    case Qualifying = 'qualifying';
    case Race = 'race';
    case WarmUp = 'warmup';
    case TimeTrial = 'timetrial';

    public function label(): string
    {
      return match($this)
      {
        self::Practice => 'Practice',
        self::Qualifying => 'Qualifying',
        self::Race => 'Race',
        self::WarmUp => 'Warm Up',
        self::TimeTrial => 'Time Trial',
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