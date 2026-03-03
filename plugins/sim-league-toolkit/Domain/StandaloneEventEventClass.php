<?php

  namespace SLTK\Domain;

  use SLTK\Core\Constants;
  use SLTK\Domain\Abstractions\ProvidesPersistableArray;
  use SLTK\Domain\Abstractions\ValueObject;
  use SLTK\Domain\Traits\HasIdentity;
  use stdClass;

  class StandaloneEventEventClass implements ValueObject, ProvidesPersistableArray {
    use HasIdentity;

    private string $carClass = '';
    private string $driverCategory = '';
    private int $driverCategoryId = Constants::DEFAULT_ID;
    private int $eventClassId;
    private string $game = '';
    private int $gameId = Constants::DEFAULT_ID;
    private bool $isBuiltIn = false;
    private bool $isInUse = false;
    private bool $isSingleCarClass = false;
    private string $name = '';
    private ?int $maxEntrants = null;
    private ?int $singleCarId = null;
    private ?string $singleCarName = null;
    private int $standaloneEventId = 0;

    public static function fromStdClass(?stdClass $data): ?self {
      $result = new self();
      $result->setCarClass($data->carClass);
      $result->setStandaloneEventId($data->standaloneEventId);
      $result->setDriverCategoryId($data->driverCategoryId);
      $result->setDriverCategory($data->driverCategory);
      $result->setEventClassId($data->eventClassId);
      $result->setGameId($data->gameId);
      $result->setGame($data->game);
      $result->setIsBuiltIn($data->isBuiltIn);
      $result->setIsInUse($data->isInUse > 0);
      $result->setIsSingleCarClass($data->isSingleCarClass);
      $result->setName($data->name);
      $result->setMaxEntrants(isset($data->maxEntrants) ? (int)$data->maxEntrants : null);
      $result->setSingleCarId($data->singleCarId ?? null);
      $result->setSingleCarName($data->singleCarName ?? null);

      return $result;
    }

    public function getCarClass(): string {
      return $this->carClass ?? '';
    }

    public function getDriverCategory(): string {
      return $this->driverCategory ?? '';
    }

    public function getDriverCategoryId(): int {
      return $this->driverCategoryId ?? Constants::DEFAULT_ID;
    }

    public function getEventClassId(): int {
      return $this->eventClassId;
    }

    public function getGame(): string {
      return $this->game ?? '';
    }

    public function getGameId(): int {
      return $this->gameId ?? Constants::DEFAULT_ID;
    }

    public function getIsBuiltIn(): bool {
      return $this->isBuiltIn;
    }

    public function getMaxEntrants(): ?int {
      return $this->maxEntrants;
    }

    public function getIsInUse(): bool {
      return $this->isInUse ?? false;
    }

    public function setIsInUse(bool $value): void {
      $this->isInUse = $value;
    }

    public function getIsSingleCarClass(): bool {
      return $this->isSingleCarClass ?? false;
    }

    public function getName(): string {
      return $this->name ?? '';
    }

    public function getSingleCarId(): ?int {
      return $this->singleCarId ?? null;
    }

    public function getSingleCarName(): string {
      return $this->singleCarName ?? '';
    }

    public function getStandaloneEventId(): int {
      return $this->standaloneEventId;
    }

    public function toArray(): array {
      return [
        'carClass' => $this->getCarClass(),
        'standaloneEventId' => $this->getStandaloneEventId(),
        'driverCategoryId' => $this->getDriverCategoryId(),
        'eventClassId' => $this->getEventClassId(),
        'gameId' => $this->getGameId(),
        'isSingleCarClass' => $this->getIsSingleCarClass(),
        'name' => $this->getName(),
        'singleCarId' => $this->getSingleCarId(),
      ];
    }

    public function toDto(): array {
      return [
        'id' => $this->getId(),
        'carClass' => $this->getCarClass(),
        'standaloneEventId' => $this->getStandaloneEventId(),
        'driverCategoryId' => $this->getDriverCategoryId(),
        'driverCategory' => $this->getDriverCategory(),
        'eventClassId' => $this->getEventClassId(),
        'game' => $this->getGame(),
        'gameId' => $this->getGameId(),
        'isBuiltIn' => $this->getIsBuiltIn(),
        'isInUse' => $this->getIsInUse(),
        'isSingleCarClass' => $this->getIsSingleCarClass(),
        'name' => $this->getName(),
        'singleCarName' => $this->getSingleCarName(),
        'singleCarId' => $this->getSingleCarId(),
        'maxEntrants' => $this->getMaxEntrants(),
      ];
    }

    private function setCarClass(string $carClass): void {
      $this->carClass = $carClass;
    }

    private function setDriverCategory(string $driverCategory): void {
      $this->driverCategory = $driverCategory;
    }

    private function setDriverCategoryId(int $driverCategoryId): void {
      $this->driverCategoryId = $driverCategoryId;
    }

    private function setEventClassId(int $eventClassId): void {
      $this->eventClassId = $eventClassId;
    }

    private function setGame(string $game): void {
      $this->game = $game;
    }

    private function setGameId(int $gameId): void {
      $this->gameId = $gameId;
    }

    private function setIsBuiltIn(bool $isBuiltIn): void {
      $this->isBuiltIn = $isBuiltIn;
    }

    private function setIsSingleCarClass(bool $isSingleCarClass): void {
      $this->isSingleCarClass = $isSingleCarClass;
    }

    private function setName(string $value): void {
      $this->name = $value;
    }

    private function setMaxEntrants(?int $value): void {
      $this->maxEntrants = $value;
    }

    private function setSingleCarId(?int $singleCarId): void {
      $this->singleCarId = $singleCarId;
    }

    private function setSingleCarName(?string $value): void {
      $this->singleCarName = $value;
    }

    private function setStandaloneEventId(int $standaloneEventId): void {
      $this->standaloneEventId = $standaloneEventId;
    }
  }
