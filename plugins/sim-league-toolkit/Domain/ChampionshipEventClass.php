<?php

  namespace SLTK\Domain;

  use Exception;
  use SLTK\Core\Constants;
  use SLTK\Database\Repositories\EventClassesRepository;
  use stdClass;

  class ChampionshipEventClass {

    private string $carClass = '';
    private int $championshipId = 0;
    private string $driverCategory = '';
    private int $driverCategoryId = Constants::DEFAULT_ID;
    private int $eventClassId;
    private string $game = '';
    private int $gameId = Constants::DEFAULT_ID;
    private bool $isBuiltIn = false;
    private bool $isInUse = false;
    private bool $isSingleCarClass = false;
    private string $name = '';
    private ?int $singleCarId = null;
    private ?string $singleCarName = null;

    public function __construct(?stdClass $data = null) {
      if ($data != null) {
        $this->carClass = $data->carClass;
        $this->championshipId = $data->championshipId;
        $this->driverCategoryId = $data->driverCategoryId;
        $this->driverCategory = $data->driverCategory;
        $this->eventClassId = $data->eventClassId;
        $this->gameId = $data->gameId;
        $this->game = $data->game;
        $this->isBuiltIn = $data->isBuiltIn;
        $this->isInUse = $data->isInUse > 0;
        $this->isSingleCarClass = $data->isSingleCarClass;
        $this->name = $data->name;
        $this->singleCarId = $data->singleCarId ?? null;
        $this->singleCarName = $data->singleCarName ?? null;
      }
    }

    public function getCarClass(): string {
      return $this->carClass ?? '';
    }

    public function getChampionshipId(): int {
      return $this->championshipId;
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

    public function getIsInUse(): bool {
      return $this->isInUse ?? false;
    }

    /**
     * @throws Exception
     */
    public function isInUse(): bool {
      return EventClassesRepository::isInUse($this->getId());
    }

    public function setIsInUse(string $value): void {
      $this->isInUse = $value;
    }

    public function getIsSingleCarClass(): bool {
      return $this->isSingleCarClass ?? false;
    }

    public function getName(): string {
      return $this->name ?? '';
    }

    public function setName(string $value): void {
      $this->name = $value;
    }

    public function getSingleCarId(): ?int {
      return $this->singleCarId ?? null;
    }

    public function getSingleCarName(): string {
      return $this->singleCarName ?? '';
    }

    public function toDto(): array {
      return [
        'carClass' => $this->getCarClass(),
        'championshipId' => $this->getChampionshipId(),
        'driverCategory' => $this->getDriverCategory(),
        'eventClassId' => $this->getEventClassId(),
        'game' => $this->getGame(),
        'isBuiltIn' => $this->getIsBuiltIn(),
        'isInUse' => $this->getIsInUse(),
        'isSingleCarClass' => $this->getIsSingleCarClass(),
        'name' => $this->getName(),
        'singleCarName' => $this->getSingleCarName(),
      ];
    }
  }