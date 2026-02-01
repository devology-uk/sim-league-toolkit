<?php

  namespace SLTK\Domain;

  use SLTK\Database\Repositories\DriverCategoriesRepository;
  use SLTK\Domain\Abstractions\AggregateRoot;
  use SLTK\Domain\Traits\HasIdentity;
  use stdClass;

  class DriverCategory implements AggregateRoot {
    use HasIdentity;

    private string $name = '';
    private int $participationRequirement = 0;
    private string $plaque = '';

    public static function fromStdClass(?stdClass $data): ?self {
      $result = new self();

      $result->setName($data->name);
      $result->setParticipationRequirement($data->participationRequirement);
      $result->setPlaque($data->plaque);

      return $result;
    }

    public static function get(int $id): DriverCategory|null {
      return null;
    }

    /**
     * @return DriverCategory[]
     */
    public static function list(): array {
      $queryResults = DriverCategoriesRepository::list();

      return self::mapDriverCategories($queryResults);
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

    public function toDto(): array {
      return [
        'id' => $this->getId(),
        'name' => $this->getName(),
        'plaque' => $this->getPlaque(),
        'participationRequirement' => $this->getParticipationRequirement(),
      ];
    }

    private static function mapDriverCategories(array $queryResults): array {
      return array_map(function ($item) {
        return self::fromStdClass($item);
      }, $queryResults);
    }

    private function setName(string $value): void {
      $this->name = $value;
    }

    private function setParticipationRequirement(int $participationRequirement): void {
      $this->participationRequirement = $participationRequirement;
    }

    private function setPlaque(string $plaque): void {
      $this->plaque = $plaque;
    }
  }