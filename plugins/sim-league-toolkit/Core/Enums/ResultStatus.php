<?php

  namespace SLTK\Core\Enums;

  enum ResultStatus: string
  {
    case Finished = 'finished';
    case DNF = 'dnf';
    case DNS = 'dns';
    case DSQ = 'dsq';

    public function label(): string
    {
      return match($this)
      {
        self::Finished => 'Finished',
        self::DNF => 'DNF',
        self::DNS => 'DNS',
        self::DSQ => 'DSQ',
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
