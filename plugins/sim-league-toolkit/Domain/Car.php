<?php

  namespace SLTK\Domain;

  use SLTK\Core\Constants;
  use stdClass;

  class Car {

    private string $carKey = '';
    private string $carClass = '';
    private int $gameId = Constants::DEFAULT_ID;
    private int $id = Constants::DEFAULT_ID;
    private string $manufacturer = '';
    private string $name = '';
    private int $year = 0;

    public function __construct(stdClass $data = null) {
      if ($data) {
        $this->gameId = $data->gameId;
        $this->carClass = $data->carClass;
        $this->carKey = $data->carKey;
        $this->name = $data->name;
        $this->year = $data->year;
        $this->manufacturer = $data->manufacturer;

        if (isset($data->id)) {
          $this->id = $data->id;
        }
      }

    }

    public function getCarClass(): string {
      return $this->carClass;
    }

    public function getCarKey(): string {
      return $this->carKey;
    }

    public function getGameId(): int {
      return $this->gameId;
    }

    public function getId(): int {
      return $this->id;
    }

    public function getManufacturer(): string {
      return $this->manufacturer;
    }

    public function getName(): string {
      return $this->name;
    }

    public function getYear(): int {
      return $this->year;
    }

    public function toArray(): array {
      $result = [
        'carClass' => $this->carClass,
        'carKey' => $this->carKey,
        'gameId' => $this->gameId,
        'manufacturer' => $this->manufacturer,
        'name' => $this->name,
        'year' => $this->year,
      ];

      if ($this->id !== Constants::DEFAULT_ID) {
        $result['id'] = $this->id;
      }

      return $result;

    }
  }