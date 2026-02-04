<?php

  namespace SLTK\Domain;

  use SLTK\Domain\Abstractions\ValueObject;
  use SLTK\Domain\Traits\HasIdentity;
  use stdClass;

  class Platform implements ValueObject {
    use HasIdentity;

    private string $name = '';
    private string $playerIdPrefix = '';
    private string $shortName = '';


    public static function fromStdClass(?stdClass $data): ?self {
      if (!$data) {
        return null;
      }

      $result = new self();

      $result->setId((int) $data->id);
      $result->setName($data->name);
      $result->setPlayerIdPrefix($data->playerIdPrefix);
      $result->setShortName($data->shortName);

      return $result;
    }

    public function getName(): string {
      return $this->name;
    }

    public function getPlayerIdPrefix(): string {
      return $this->playerIdPrefix;
    }

    public function getShortName(): string {
      return $this->shortName;
    }

    public function toDto(): array {
      return [
        'id' => $this->getId(),
        'name' => $this->getName(),
        'shortName' => $this->getShortName(),
        'playerIdPrefix' => $this->getPlayerIdPrefix(),
      ];
    }

    private function setName(string $value): void {
      $this->name = $value;
    }

    private function setPlayerIdPrefix(string $value): void {
      $this->playerIdPrefix = $value;
    }

    private function setShortName(string $value): void {
      $this->shortName = $value;
    }
  }
