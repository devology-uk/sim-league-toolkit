<?php

  namespace SLTK\Domain;

  use SLTK\Core\Constants;
  use SLTK\Database\Repositories\DriverCategoriesRepository;
  use stdClass;

  class DriverCategory extends DomainBase {
    private string $name = '';
    private int $participationRequirement = 0;
    private string $plaque = '';

    public function __construct(stdClass $data = null) {

      if ($data !== null) {
        $this->name = $data->name;
        $this->plaque = $data->plaque;
        $this->participationRequirement = $data->participationRequirement;

        if (isset($data->id)) {
          $this->id = $data->id;
        }
      }

    }

    public static function get(int $id): DriverCategory|null {
      return null;
    }

    public static function list(): array {
      $queryResults = DriverCategoriesRepository::list();

      return self::mapDriverCategories($queryResults);
    }

    public function getId(): int {
      return $this->id;
    }

    public function getName(): string {
      return $this->name;
    }

    public function getParticipationRequirement(): int {
      return $this->participationRequirement;
    }

    public function getPlaque(): string {
      return $this->plaque;
    }

    public function save(): bool {
      return false;
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

    private static function mapDriverCategories(array $queryResults): array {
      $results = array();

      foreach ($queryResults as $item) {
        $results[] = new DriverCategory($item);
      }

      return $results;
    }
  }