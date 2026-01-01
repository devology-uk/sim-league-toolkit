<?php

  namespace SLTK\Domain;

  use Exception;
  use SLTK\Core\CommonFieldNames;
  use SLTK\Core\Constants;
  use SLTK\Database\Repositories\EventClassesRepository;
  use stdClass;

  class EventClass extends DomainBase {
    public final const string CAR_CLASS_FIELD_NAME = 'sltk-car-class';
    public final const string DRIVER_CATEGORY_FIELD_NAME = 'sltk-driver-category';
    public final const string EVENT_CLASS_ID_FIELD_NAME = 'sltk-event-class-id';
    public final const string GAME_ID_FIELD_NAME = CommonFieldNames::GAME_ID;
    public final const string IS_SINGLE_CAR_CLASS_FIELD_NAME = 'sltk-is-single-car-class';
    public final const string NAME_FIELD_NAME = CommonFieldNames::NAME;
    public final const string SINGLE_CAR_ID_FIELD_NAME = 'sltk-single-car-id';

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

    public function __construct(?stdClass $data = null) {
      if ($data != null) {
        $this->carClass = $data->carClass;
        $this->driverCategoryId = $data->driverCategoryId;
        $this->driverCategory = $data->driverCategory;
        $this->gameId = $data->gameId;
        $this->game = $data->game;
        $this->isBuiltIn = $data->isBuiltIn;
        $this->isSingleCarClass = $data->isSingleCarClass;
        $this->name = $data->name;
        $this->singleCarId = $data->singleCarId ?? null;
        $this->singleCarName = $data->singleCarName ?? null;

        if (isset($data->id)) {
          $this->id = $data->id;
        }
      }
    }

    /**
     * @throws Exception
     */
    public static function delete(int $id): void {
      EventClassesRepository::delete($id);
    }

    /**
     * @throws Exception
     */
    public static function get(int $id): EventClass|null {
      $queryResult = EventClassesRepository::getById($id);

      return new EventClass($queryResult);
    }

    /**
     * @throws Exception
     */
    public static function list(): array {
      $queryResult = EventClassesRepository::list();

      return self::mapEventClasses($queryResult);
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

    public function getId(): int {
      return $this->id ?? Constants::DEFAULT_ID;
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

    public function getSingleCarName(): string {
      return $this->singleCarName ?? '';
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
    public function save(): bool {
      try {
        if ($this->id == Constants::DEFAULT_ID) {
          $this->id = EventClassesRepository::add($this->toArray());
        } else {
          EventClassesRepository::update($this->id, $this->toArray());
        }

        return true;
      } catch (Exception) {
        return false;
      }
    }

    public function toArray(): array {
      $result = [
        'carClass' => $this->getCarClass(),
        'driverCategoryId' => $this->getDriverCategoryId(),
        'gameId' => $this->getGameId(),
        'isBuiltIn' => $this->getIsBuiltIn(),
        'isSingleCarClass' => $this->getIsSingleCarClass(),
        'name' => $this->getName(),
        'singleCarId' => $this->getSingleCarId(),
      ];

      if ($this->id != Constants::DEFAULT_ID) {
        $result['id'] = $this->id;
      }

      return $result;
    }

    /**
     * @throws Exception
     */
    public function toDto(): array {
      return [
        'id' => $this->id,
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

    public function toTableItem(): array {
      $result = [
        'carClass' => $this->getCarClass(),
        'driverCategory' => $this->getDriverCategory(),
        'game' => $this->getGame(),
        'isBuiltIn' => $this->getIsBuiltIn() ? esc_html__('Yes', 'sim-league-toolkit') : esc_html__('No', 'sim-league-toolkit'),
        'isSingleCarClass' => $this->getIsSingleCarClass() ? esc_html__('Yes', 'sim-league-toolkit') : esc_html__('No', 'sim-league-toolkit'),
        'name' => $this->getName(),
        'singleCarName' => $this->getSingleCarName(),
      ];

      if (isset($this->id)) {
        $result['id'] = $this->id;
      }

      return $result;
    }

    public function validate(): ValidationResult {
      $result = new ValidationResult();

      if ($this->getGameId() < 1) {
        $result->addValidationError(self::GAME_ID_FIELD_NAME, esc_html__('You must select the game for the event class', 'sim-league-toolkit'));
      }

      if (!$this->getName()) {
        $result->addValidationError(self::NAME_FIELD_NAME, esc_html__('You must provide a name for the event class', 'sim-league-toolkit'));
      }

      if ($this->getDriverCategoryId() < 1) {
        $result->addValidationError(self::DRIVER_CATEGORY_FIELD_NAME, esc_html__('You must select the driver category for the event class', 'sim-league-toolkit'));
      }

      if (!$this->getCarClass()) {
        $result->addValidationError(self::CAR_CLASS_FIELD_NAME, esc_html__('You must select the car class for the event class', 'sim-league-toolkit'));
      }

      if ($this->getIsSingleCarClass() && $this->getSingleCarId() < 1) {
        $result->addValidationError(self::SINGLE_CAR_ID_FIELD_NAME, esc_html__('When Is Single Car Class is selected you must select the single car for the event class', 'sim-league-toolkit'));
      }

      return $result;

    }

    private static function mapEventClasses(array $queryResults): array {
      $results = array();

      foreach ($queryResults as $item) {
        $results[] = new EventClass($item);
      }

      return $results;
    }
  }