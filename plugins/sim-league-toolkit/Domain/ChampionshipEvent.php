<?php

  namespace SLTK\Domain;

  use SLTK\Core\Constants;
  use SLTK\Domain\Abstractions\EventBase;
  use SLTK\Domain\Abstractions\ProvidesPersistableArray;
  use SLTK\Domain\Abstractions\ValueObject;
  use stdClass;

  class ChampionshipEvent extends EventBase implements ValueObject, ProvidesPersistableArray {
    private string $championship = '';
    private int $championshipId = Constants::DEFAULT_ID;
    private int $round = 1;

    public static function fromStdClass(?stdClass $data): ?self {
      if (!$data) {
        return null;
      }

      $result = new self();
      $result->hydrateFromStdClass($data);
      $result->setChampionshipId($data->championshipId);
      $result->setChampionship($data->championship);
      $result->setRound($data->round);

      return $result;
    }

    public function getChampionship(): string {
      return $this->championship;
    }

    public function getChampionshipId(): int {
      return $this->championshipId;
    }

    public function setChampionshipId(int $value): void {
      $this->championshipId = $value;
    }

    public function getEventType(): string {
      return 'championship';
    }

    public function getRound(): int {
      return $this->round;
    }

    public function setRound(int $value): void {
      $this->round = $value;
    }

    public function toArray(): array {
      $result = $this->commonToArray();
      $result['championshipId'] = $this->getChampionshipId();
      $result['round'] = $this->getRound();

      if ($this->hasId()) {
        $result['id'] = $this->getId();
      }

      return $result;
    }

    public function toDto(): array {
      $result = $this->commonToDto();
      $result['eventType'] = $this->getEventType();
      $result['championshipId'] = $this->getChampionshipId();
      $result['championship'] = $this->getChampionship();
      $result['round'] = $this->getRound();

      return $result;
    }

    private function setChampionship(string $championship): void {
      $this->championship = $championship;
    }
  }