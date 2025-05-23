<?php

  namespace SLTK\Domain;

  use SLTK\Core\Constants;
  use stdClass;

  class DriverCategory {
    private int $gameId = Constants::DEFAULT_ID;
    private int $id = Constants::DEFAULT_ID;
    private string $name = '';
    private string $plaque = '';

    public function __construct(stdClass $data = null) {

      if ($data !== null) {
        $this->gameId = $data->gameId;
        $this->name = $data->name;
        $this->plaque = $data->plaque;

        if (isset($data->id)) {
          $this->id = $data->id;
        }
      }

    }

    public function getGameId(): int {
      return $this->gameId;
    }

    public function getId(): int {
      return $this->id;
    }

    public function getName(): string {
      return $this->name;
    }

    public function getPlaque(): string {
      return $this->plaque;
    }

    public function toArray(): array {
      $result = [
        'gameId' => $this->gameId,
        'name' => $this->name,
        'plaque' => $this->plaque
      ];

      if ($this->id !== Constants::DEFAULT_ID) {
        $result['id'] = $this->id;
      }

      return $result;
    }
  }