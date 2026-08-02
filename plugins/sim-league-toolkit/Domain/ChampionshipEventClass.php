<?php

  namespace SLTK\Domain;

  use SLTK\Domain\Abstractions\ProvidesPersistableArray;
  use SLTK\Domain\Abstractions\ValueObject;
  use SLTK\Domain\Traits\HasEventClassFields;
  use stdClass;

  class ChampionshipEventClass implements ValueObject, ProvidesPersistableArray {
    use HasEventClassFields;

    private string $championship = '';
    private int $championshipId = 0;

    public static function fromStdClass(?stdClass $data): ?self {
      $result = new self();
      $result->setChampionshipId($data->championshipId);
      $result->hydrateEventClassFieldsFromStdClass($data);

      return $result;
    }

    public function getChampionship(): string {
      return $this->championship ?? '';
    }

    public function getChampionshipId(): int {
      return $this->championshipId;
    }

    public function toArray(): array {
      return array_merge($this->eventClassFieldsToArray(), [
        'championshipId' => $this->getChampionshipId(),
      ]);
    }

    public function toDto(): array {
      return array_merge($this->eventClassFieldsToDto(), [
        'championshipId' => $this->getChampionshipId(),
        'championship' => $this->getChampionship(),
      ]);
    }

    private function setChampionship(string $championship): void {
      $this->championship = $championship;
    }

    private function setChampionshipId(int $championshipId): void {
      $this->championshipId = $championshipId;
    }
  }
