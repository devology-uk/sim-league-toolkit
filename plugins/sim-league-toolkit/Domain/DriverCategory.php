<?php

  namespace SLTK\Domain;

  use SLTK\Core\Constants;
  use stdClass;

  class DriverCategory {
    private int $id = Constants::DEFAULT_ID;
    private string $name = '';
    private string $plaque = '';
    private int $participationRequirement = 0;

    public function __construct(stdClass $data = null) {

      if ($data !== null) {
        $this->name = $data->name;
        $this->plaque = $data->plaque;
        $this->participationRequirement = $data->participation_requirement;

        if (isset($data->id)) {
          $this->id = $data->id;
        }
      }

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

    public function getParticipationRequirement(): int {
      return $this->participationRequirement;
    }

    public function toArray(): array {
      $result = [
        'name' => $this->name,
        'plaque' => $this->plaque,
        'participationRequirement' => $this->participationRequirement,
      ];

      if ($this->id !== Constants::DEFAULT_ID) {
        $result['id'] = $this->id;
      }

      return $result;
    }
  }