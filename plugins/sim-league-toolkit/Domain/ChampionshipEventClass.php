<?php

  namespace SLTK\Domain;

  use Exception;
  use SLTK\Core\CommonFieldNames;
  use SLTK\Core\Constants;
  use SLTK\Database\Repositories\EventClassesRepository;
  use stdClass;

  class ChampionshipEventClass extends EntityBase {

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

    public function getCarClass(): string {
      return $this->carClass ?? '';
    }

    public function getDriverCategory(): string {
      return $this->driverCategory ?? '';
    }

    public function getDriverCategoryId(): int {
      return $this->driverCategoryId ?? Constants::DEFAULT_ID;
    }

    public function getGame(): string {
      return $this->game ?? '';
    }

    public function getGameId(): int {
      return $this->gameId ?? Constants::DEFAULT_ID;
    }

    public function getId(): int {
      return $this->id ?? Constants::DEFAULT_ID;
    }

    public function getIsBuiltIn(): bool {
      return $this->isBuiltIn;
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

    /**
     * @throws Exception
     */
    public function isInUse(): bool {
      return EventClassesRepository::isInUse($this->getId());
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
  }