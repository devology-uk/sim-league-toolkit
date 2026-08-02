<?php

  namespace SLTK\Domain;

  use SLTK\Domain\Abstractions\ProvidesPersistableArray;
  use SLTK\Domain\Abstractions\ValueObject;
  use SLTK\Domain\Traits\HasEventClassFields;
  use stdClass;

  class StandaloneEventEventClass implements ValueObject, ProvidesPersistableArray {
    use HasEventClassFields;

    private int $standaloneEventId = 0;

    public static function fromStdClass(?stdClass $data): ?self {
      $result = new self();
      $result->setStandaloneEventId($data->standaloneEventId);
      $result->hydrateEventClassFieldsFromStdClass($data);

      return $result;
    }

    public function getStandaloneEventId(): int {
      return $this->standaloneEventId;
    }

    public function toArray(): array {
      return array_merge($this->eventClassFieldsToArray(), [
        'standaloneEventId' => $this->getStandaloneEventId(),
      ]);
    }

    public function toDto(): array {
      return array_merge($this->eventClassFieldsToDto(), [
        'standaloneEventId' => $this->getStandaloneEventId(),
      ]);
    }

    private function setStandaloneEventId(int $standaloneEventId): void {
      $this->standaloneEventId = $standaloneEventId;
    }
  }
