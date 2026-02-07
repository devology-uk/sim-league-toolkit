<?php

  namespace SLTK\Domain;

  use Exception;
  use SLTK\Core\Constants;
  use SLTK\Database\Repositories\EventClassesRepository;
  use SLTK\Domain\Abstractions\AggregateRoot;
  use SLTK\Domain\Abstractions\Deletable;
  use SLTK\Domain\Abstractions\Listable;
  use SLTK\Domain\Abstractions\ProvidesPersistableArray;
  use SLTK\Domain\Abstractions\Saveable;
  use SLTK\Domain\Traits\HasIdentity;
  use stdClass;

  class EventClass implements AggregateRoot, Deletable, Listable, ProvidesPersistableArray, Saveable {
    use HasIdentity;

    private string $carClass = '';
    private string $driverCategory = '';
    private int $driverCategoryId = Constants::DEFAULT_ID;
    private string $game = '';
    private int $gameId = Constants::DEFAULT_ID;
    private bool $isBuiltIn = false;
    private bool $isSingleCarClass = false;
    private string $name = '';
    private ?int $singleCarId = null;
    private ?string $singleCarName = null;

    /**
     * @throws Exception
     */
    public static function delete(int $id): void {
      EventClassesRepository::delete($id);
    }

    public static function fromStdClass(?stdClass $data): ?self {
      if (!$data) {
        return null;
      }

      $result = new self();
      $result->setId($data->id);
      $result->setCarClass($data->carClass);
      $result->setDriverCategory($data->driverCategory);
      $result->setDriverCategoryId($data->driverCategoryId);
      $result->setGame($data->game);
      $result->setGameId($data->gameId);
      $result->setIsBuiltIn($data->isBuiltIn);
      $result->setIsSingleCarClass($data->isSingleCarClass);
      $result->setName($data->name);
      $result->setSingleCarId($data->singleCarId ?? null);
      $result->setSingleCarName($data->singleCarName ?? null);


      return $result;
    }

    /**
     * @throws Exception
     */
    public static function get(int $id): EventClass|null {
      $queryResult = EventClassesRepository::getById($id);

      return EventClass::fromStdClass($queryResult);
    }

    /**
     * @return EventClass[]
     * @throws Exception
     */
    public static function list(): array {
      $queryResult = EventClassesRepository::list();

      return array_map(function (stdClass $item) {
        return EventClass::fromStdClass($item);
      }, $queryResult);
    }

    /**
     * @return EventClass[]
     * @throws Exception
     */
    public static function listForGame(int $gameId): array {
      $queryResult = EventClassesRepository::listForGame($gameId);

      return array_map(function (stdClass $item) {
        return EventClass::fromStdClass($item);
      }, $queryResult);
    }

    /**
     * @throws Exception
     */
    public function canDelete(): bool {
      return !$this->getIsBuiltIn() && !$this->isInUse();
    }

    public function getCarClass(): string {
      return $this->carClass ?? '';
    }

    public function setCarClass(string $value): void {
      $this->carClass = $value;
    }

    public function getDriverCategory(): string {
      return $this->driverCategory ?? '';
    }

    public function getDriverCategoryId(): int {
      return $this->driverCategoryId ?? Constants::DEFAULT_ID;
    }

    public function setDriverCategoryId(int $value): void {
      $this->driverCategoryId = $value;
    }

    public function getGame(): string {
      return $this->game ?? '';
    }

    public function getGameId(): int {
      return $this->gameId ?? Constants::DEFAULT_ID;
    }

    public function setGameId(int $value): void {
      $this->gameId = $value;
    }

    public function getIsBuiltIn(): bool {
      return $this->isBuiltIn;
    }

    public function setIsBuiltIn(bool $value): void {
      $this->isBuiltIn = $value;
    }

    public function getIsSingleCarClass(): bool {
      return $this->isSingleCarClass ?? false;
    }

    public function setIsSingleCarClass(bool $value): void {
      $this->isSingleCarClass = $value;
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

    public function setSingleCarId(?int $value): void {
      $this->singleCarId = $value;
    }

    public function getSingleCarName(): ?string {
      return $this->singleCarName;
    }

    /**
     * @throws Exception
     */
    public function isInUse(): bool {
      return EventClassesRepository::isInUse($this->getId());
    }

    /**
     * @throws Exception
     */
    public function save(): self {
      if ($this->getId() == Constants::DEFAULT_ID) {
        $this->setId(EventClassesRepository::add($this->toArray()));
      } else {
        EventClassesRepository::update($this->getId(), $this->toArray());
      }

      return $this;
    }

    public function toArray(): array {
      return [
        'carClass' => $this->getCarClass(),
        'driverCategoryId' => $this->getDriverCategoryId(),
        'gameId' => $this->getGameId(),
        'isBuiltIn' => $this->getIsBuiltIn(),
        'isSingleCarClass' => $this->getIsSingleCarClass(),
        'name' => $this->getName(),
        'singleCarId' => $this->getSingleCarId(),
      ];
    }

    /**
     * @throws Exception
     */
    public function toDto(): array {
      return [
        'id' => $this->getId(),
        'carClass' => $this->getCarClass(),
        'driverCategory' => $this->getDriverCategory(),
        'driverCategoryId' => $this->getDriverCategoryId(),
        'game' => $this->getGame(),
        'gameId' => $this->getGameId(),
        'isBuiltIn' => $this->getIsBuiltIn(),
        'isSingleCarClass' => $this->getIsSingleCarClass(),
        'name' => $this->getName(),
        'singleCarId' => $this->getSingleCarId(),
        'singleCarName' => $this->getSingleCarName(),
        'isInUse' => $this->isInUse(),
      ];
    }

    private function setDriverCategory(string $driverCategory): void {
      $this->driverCategory = $driverCategory;
    }

    private function setGame(string $game): void {
      $this->game = $game;
    }

    private function setSingleCarName(?string $singleCarName): void {
      $this->singleCarName = $singleCarName;
    }
  }