<?php

  namespace SLTK\Domain\Traits;

  use SLTK\Core\Constants;
  use stdClass;

  trait HasEventClassFields {
    private string $carClass = '';
    private string $driverCategory = '';
    private int $driverCategoryId = Constants::DEFAULT_ID;
    private int $eventClassId;
    private string $game = '';
    private int $gameId = Constants::DEFAULT_ID;
    private bool $isBuiltIn = false;
    private bool $isInUse = false;
    private bool $isSingleCarClass = false;
    private ?int $maxEntrants = null;
    private string $name = '';
    private ?int $singleCarId = null;
    private ?string $singleCarName = null;

    public function getCarClass(): string {
      return $this->carClass ?? '';
    }

    private function setCarClass(string $carClass): void {
      $this->carClass = $carClass;
    }

    public function getDriverCategory(): string {
      return $this->driverCategory ?? '';
    }

    private function setDriverCategory(string $driverCategory): void {
      $this->driverCategory = $driverCategory;
    }

    public function getDriverCategoryId(): int {
      return $this->driverCategoryId ?? Constants::DEFAULT_ID;
    }

    private function setDriverCategoryId(int $driverCategoryId): void {
      $this->driverCategoryId = $driverCategoryId;
    }

    public function getEventClassId(): int {
      return $this->eventClassId;
    }

    private function setEventClassId(int $eventClassId): void {
      $this->eventClassId = $eventClassId;
    }

    public function getGame(): string {
      return $this->game ?? '';
    }

    private function setGame(string $game): void {
      $this->game = $game;
    }

    public function getGameId(): int {
      return $this->gameId ?? Constants::DEFAULT_ID;
    }

    private function setGameId(int $gameId): void {
      $this->gameId = $gameId;
    }

    public function getIsBuiltIn(): bool {
      return $this->isBuiltIn;
    }

    private function setIsBuiltIn(bool $isBuiltIn): void {
      $this->isBuiltIn = $isBuiltIn;
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

    private function setIsSingleCarClass(bool $isSingleCarClass): void {
      $this->isSingleCarClass = $isSingleCarClass;
    }

    public function getMaxEntrants(): ?int {
      return $this->maxEntrants;
    }

    private function setMaxEntrants(?int $value): void {
      $this->maxEntrants = $value;
    }

    public function getName(): string {
      return $this->name ?? '';
    }

    private function setName(string $value): void {
      $this->name = $value;
    }

    public function getSingleCarId(): ?int {
      return $this->singleCarId ?? null;
    }

    private function setSingleCarId(?int $singleCarId): void {
      $this->singleCarId = $singleCarId;
    }

    public function getSingleCarName(): string {
      return $this->singleCarName ?? '';
    }

    private function setSingleCarName(?string $value): void {
      $this->singleCarName = $value;
    }

    private function eventClassFieldsToArray(): array {
      return [
        'carClass' => $this->getCarClass(),
        'driverCategoryId' => $this->getDriverCategoryId(),
        'eventClassId' => $this->getEventClassId(),
        'gameId' => $this->getGameId(),
        'isSingleCarClass' => $this->getIsSingleCarClass(),
        'name' => $this->getName(),
        'singleCarId' => $this->getSingleCarId(),
      ];
    }

    private function eventClassFieldsToDto(): array {
      return [
        'carClass' => $this->getCarClass(),
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

    private function hydrateEventClassFieldsFromStdClass(stdClass $data): void {
      $this->setCarClass($data->carClass);
      $this->setDriverCategoryId($data->driverCategoryId);
      $this->setDriverCategory($data->driverCategory);
      $this->setEventClassId($data->eventClassId);
      $this->setGameId($data->gameId);
      $this->setGame($data->game);
      $this->setIsBuiltIn($data->isBuiltIn);
      $this->setIsInUse($data->isInUse > 0);
      $this->setIsSingleCarClass($data->isSingleCarClass);
      $this->setName($data->name);
      $this->setMaxEntrants(isset($data->maxEntrants) ? (int)$data->maxEntrants : null);
      $this->setSingleCarId($data->singleCarId ?? null);
      $this->setSingleCarName($data->singleCarName ?? null);
    }
  }
