<?php

  namespace SLTK\Domain;

  use SLTK\Core\Constants;
  use stdClass;

  class CarClass {
    private string $displayName = '';
    private int $gameId = Constants::DEFAULT_ID;
    private int $id = Constants::DEFAULT_ID;
    private string $name = '';

    public function __construct(stdClass $data = null) {

      if ($data !== null) {
        $this->name = $data->carClass;
      }
    }

    public function getDisplayName(): string {
      return $this->displayName;
    }

    public function getName(): string {
      return $this->name;
    }

    public function toArray(): array {
      $result = [
        'displayName' => $this->displayName,
        'gameId' => $this->gameId,
        'name' => $this->name,
      ];

      if ($this->id !== Constants::DEFAULT_ID) {
        $result['id'] = $this->id;
      }

      return $result;
    }

    public function toDto(): array {
      return [
        'id' => $this->id,
        'displayName' => $this->displayName,
        'gameId' => $this->gameId,
        'name' => $this->name,
      ];
    }
  }