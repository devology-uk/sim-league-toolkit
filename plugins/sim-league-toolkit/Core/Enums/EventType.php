<?php

  namespace SLTK\Core\Enums;

  enum EventType: string
  {
    case Championship = 'championship';
    case Standalone = 'standalone';

    public function label(): string
    {
      return match($this)
      {
        self::Championship => 'Championship Event',
        self::Standalone => 'Standalone Event',
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