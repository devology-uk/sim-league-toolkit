<?php

  namespace SLTK\Domain;

  use DateInterval;
  use DateTime;
  use Exception;
  use SLTK\Core\CommonFieldNames;
  use SLTK\Core\Constants;
  use SLTK\Database\Repositories\ChampionshipRepository;
  use SLTK\Database\Repositories\EventClassesRepository;
  use stdClass;

  class Championship extends DomainBase {

    public final const string ALLOW_ENTRY_CHANGE_FIELD_NAME = 'sltk_allow_entry_change';
    public final const string BANNER_IMAGE_FILE_FIELD_NAME = CommonFieldNames::BANNER_IMAGE_FILE;
    public final const string BANNER_IMAGE_TAB = 'banner_image';
    public final const string BANNER_IMAGE_URL_HIDDEN_FIELD_NAME = CommonFieldNames::BANNER_IMAGE_URL;
    public final const string CHAMPIONSHIP_ID_FIELD_NAME = CommonFieldNames::CHAMPIONSHIP_ID;
    public final const string DESCRIPTION_FIELD_NAME = CommonFieldNames::DESCRIPTION;
    public final const string ENTRY_CHANGE_LIMIT_FIELD_NAME = 'sltk_entry_change_limit';
    public final const string EVENT_CLASSES_TAB = 'sltk_event_classes';
    public final const string GAME_ID_FIELD_NAME = CommonFieldNames::GAME_ID;
    public final const string IS_ACTIVE_FIELD_NAME = CommonFieldNames::IS_ACTIVE;
    public final const string NAME_FIELD_NAME = CommonFieldNames::NAME;
    public final const string PLATFORM_ID_FIELD_NAME = CommonFieldNames::PLATFORM_ID;
    public final const string RESULTS_TO_DISCARD_FIELD_NAME = 'sltk_results_to_discard';
    public final const string RULE_SET_ID_FIELD_NAME = CommonFieldNames::RULE_SET_ID;
    public final const string SCORING_SET_FIELD_NAME = CommonFieldNames::SCORING_SET_ID;
    public final const string START_DATE_FIELD_NAME = CommonFieldNames::START_DATE;
    public final const string TRACK_ID_FIELD_NAME = CommonFieldNames::TRACK_ID;
    public final const string TRACK_LAYOUT_ID_FIELD_NAME = CommonFieldNames::TRACK_LAYOUT_ID;
    public final const string TYPE_FIELD_NAME = 'sltk-championship-type';

    private bool $allowEntryChange = false;
    private string $bannerImageUrl = '';
    private string $description = '';
    private int $entryChangeLimit = 0;
    private string $game = '';
    private int $gameId = Constants::DEFAULT_ID;
    private bool $isActive = false;
    private bool $isTrackMasterChampionship = false;
    private string $name = '';
    private string $platform = '';
    private int $platformId = Constants::DEFAULT_ID;
    private int $resultsToDiscard = 0;
    private ?int $ruleSetId = null;
    private int $scoringSetId = Constants::DEFAULT_ID;
    private DateTime $startDate;
    private ?int $trackMasterTrackId = null;
    private ?int $trackMasterTrackLayoutId = null;
    private bool $trophiesAwarded = false;

    public function __construct(?stdClass $data = null) {
      $this->startDate = (new DateTime())->add(new DateInterval('P1D'));
      if ($data != null) {
        $this->id = $data->id;
        $this->allowEntryChange = $data->allowEntryChange ?? false;
        $this->bannerImageUrl = $data->bannerImageUrl;
        $this->description = $data->description ?? '';
        $this->entryChangeLimit = $data->entryChangeLimit ?? 0;
        $this->game = $data->game ?? '';
        $this->gameId = $data->gameId;
        $this->isActive = $data->isActive;
        $this->isTrackMasterChampionship = $data->isTrackMasterChampionship;
        $this->name = $data->name ?? '';
        $this->platform = $data->platform ?? '';
        $this->platformId = $data->platformId ?? Constants::DEFAULT_ID;
        $this->resultsToDiscard = $data->resultsToDiscard ?? 0;
        $this->ruleSetId = $data->ruleSetId ?? null;
        $this->scoringSetId = $data->scoringSetId ?? Constants::DEFAULT_ID;
        $this->startDate = DateTime::createFromFormat(Constants::STANDARD_DATE_FORMAT, $data->startDate);
        $this->trackMasterTrackId = $data->trackMasterTrackId ?? Constants::DEFAULT_ID;
        $this->trackMasterTrackLayoutId = $data->trackMasterTrackLayoutId ?? null;
        $this->trophiesAwarded = $data->trophiesAwarded ?? false;
      }
    }

    /**
     * @throws Exception
     */
    public static function delete(int $id): void {
      ChampionshipRepository::delete($id);
    }

    /**
     * @throws Exception
     */
    public static function get(int $id): Championship|null {
      $queryResult = ChampionshipRepository::getById($id);

      return new Championship($queryResult);
    }

    /**
     * @throws Exception
     */
    public static function list(): array {
      $queryResults = ChampionshipRepository::listAll();

      return self::mapChampionships($queryResults);
    }

    public function getAllowEntryChange(): bool {
      return $this->allowEntryChange ?? false;
    }

    public function setAllowEntryChange(bool $value): void {
      $this->allowEntryChange = $value;
    }

    public function getBannerImageUrl(): string {
      return $this->bannerImageUrl ?? '';
    }

    public function setBannerImageUrl(string $value): void {
      $this->bannerImageUrl = $value;
    }

    public function getDescription(): string {
      return trim($this->description ?? '');
    }

    public function setDescription(string $value): void {
      $this->description = trim($value);
    }

    public function getEntryChangeLimit(): int {
      return $this->entryChangeLimit ?? 0;
    }

    public function setEntryChangeLimit(int $value): void {
      $this->entryChangeLimit = $value;
    }

    public function getFormattedStartDate(): string {
      return date_format($this->getStartDate(), Constants::STANDARD_DATE_FORMAT);
    }

    public function getGame(): string {
      return $this->game ?? '';
    }

    public function setGame(string $value): void {
      $this->game = $value;
    }

    public function getGameId(): int {
      return $this->gameId ?? Constants::DEFAULT_ID;
    }

    public function setGameId(int $value): void {
      $this->gameId = $value;
    }

    public function getIsActive(): bool {
      return $this->isActive ?? false;
    }

    public function setIsActive(bool $value): void {
      $this->isActive = $value;
    }

    public function getIsTrackMasterChampionship(): bool {
      return $this->isTrackMasterChampionship ?? false;
    }

    public function setIsTrackMasterChampionship(bool $value): void {
      $this->isTrackMasterChampionship = $value;
    }

    public function getName(): string {
      return trim($this->name ?? '');
    }

    public function setName(string $value): void {
      $this->name = trim($value);
    }

    public function getPlatform(): string {
      return $this->platform ?? '';
    }

    public function setPlatform(string $value): void {
      $this->platform = $value;
    }

    public function getPlatformId(): int {
      return $this->platformId ?? Constants::DEFAULT_ID;
    }

    public function setPlatformId(int $value): void {
      $this->platformId = $value;
    }

    public function getResultsToDiscard(): int {
      return $this->resultsToDiscard ?? 0;
    }

    public function setResultsToDiscard(int $value): void {
      $this->resultsToDiscard = $value;
    }

    public function getRuleSetId(): ?int {
      return $this->ruleSetId > 0 ? (int)$this->ruleSetId : null;
    }

    public function setRuleSetId(?int $value): void {
      $this->ruleSetId = $value;
    }

    public function getScoringSetId(): int {
      return $this->scoringSetId ?? Constants::DEFAULT_ID;
    }

    public function setScoringSetId(int $value): void {
      $this->scoringSetId = $value;
    }

    public function getStartDate(): DateTime {
      return $this->startDate ?? (new DateTime())->add(new DateInterval('P1D'));
    }

    public function setStartDate(DateTime $value): void {
      $this->startDate = $value;
    }

    public function getTrackMasterTrackId(): ?int {
      return $this->trackMasterTrackId > 0 ? (int)$this->trackMasterTrackId : null;
    }

    public function setTrackMasterTrackId(?int $value): void {
      $this->trackMasterTrackId = $value;
    }

    public function getTrackMasterTrackLayoutId(): ?int {
      return $this->trackMasterTrackLayoutId > 0 ? (int)$this->trackMasterTrackLayoutId : null;
    }

    public function setTrackMasterTrackLayoutId(?int $value): void {
      $this->trackMasterTrackLayoutId = $value;
    }

    public function getTrophiesAwarded(): bool {
      return $this->trophiesAwarded ?? false;
    }

    public function setTrophiesAwarded(bool $value): void {
      $this->trophiesAwarded = $value;
    }

    /**
     * @return ChampionshipEventClass[]
     * @throws Exception
     */
    public function listEventClasses(): array {
      $queryResults = EventClassesRepository::listForChampionship($this->id);

      return self::mapChampionshipEventClasses($queryResults);
    }


    private static function mapChampionshipEventClasses(array $queryResults): array {
      $results = array();

      foreach($queryResults as $item) {
        $results[] = new ChampionshipEventClass($item);
      }

      return $results;
    }
    public function save(): bool {
      try {
        if ($this->id == Constants::DEFAULT_ID) {
          $this->id = ChampionshipRepository::add($this->toArray(false));
        } else {
          ChampionshipRepository::update($this->id, $this->toArray(false));
        }
      } catch (Exception) {
        return false;
      }

      return true;
    }

    /**
     * @return array{fieldName: string, value: mixed}
     */
    public function toArray(bool $includeId = true): array {
      $result = [
        'bannerImageUrl' => $this->getBannerImageUrl(),
        'description' => $this->getDescription(),
        'gameId' => $this->getGameId(),
        'isActive' => $this->getIsActive(),
        'isTrackMasterChampionship' => $this->getIsTrackMasterChampionship(),
        'name' => $this->getName(),
        'platformId' => $this->getPlatformId(),
        'resultsToDiscard' => $this->getResultsToDiscard(),
        'ruleSetId' => $this->getRuleSetId(),
        'scoringSetId' => $this->getScoringSetId(),
        'startDate' => $this->getFormattedStartDate(),
        'trackMasterTrackId' => $this->getTrackMasterTrackId(),
        'trackMasterTrackLayoutId' => $this->getTrackMasterTrackLayoutId(),
        'trophiesAwarded' => $this->getTrophiesAwarded(),
      ];

      if ($includeId && $this->id != Constants::DEFAULT_ID) {
        $result['id'] = $this->id;
      }

      return $result;
    }

    /**
     * @return array{columnName: string, value: mixed}
     */
    public function toTableItem(): array {
      return [
        'id' => $this->id,
        'name' => $this->getName(),
        'game' => $this->getGame(),
        'platform' => $this->getPlatform(),
        'startDate' => $this->getFormattedStartDate(),
        'isActive' => $this->getIsActive() ? esc_html__('Yes', 'sim-league-toolkit') : esc_html__('No', 'sim-league-toolkit'),
      ];
    }

    public function validate(): ValidationResult {
      $result = new ValidationResult();

      if ($this->getGameId() < 1) {
        $result->addValidationError(self::GAME_ID_FIELD_NAME, esc_html__('You must select the game for the championship', 'sim-league-toolkit'));
      }

      if ($this->getPlatformId() < 1) {
        $result->addValidationError(self::PLATFORM_ID_FIELD_NAME, esc_html__('You must select the platform for the championship', 'sim-league-toolkit'));
      }

      if (!$this->getName()) {
        $result->addValidationError(self::NAME_FIELD_NAME, esc_html__('You must provide a name for the championship', 'sim-league-toolkit'));
      }

      if (!$this->getDescription()) {
        $result->addValidationError(self::DESCRIPTION_FIELD_NAME, esc_html__('You must provide a description for the championship', 'sim-league-toolkit'));
      }

      if ($this->getScoringSetId() < 1) {
        $result->addValidationError(self::SCORING_SET_FIELD_NAME, esc_html__('You must select a scoring set for the championship', 'sim-league-toolkit'));
      }

      if (!$this->getStartDate()) {
        $result->addValidationError(self::START_DATE_FIELD_NAME, esc_html__('You must provide a start date for the championship', 'sim-league-toolkit'));
      }

      if ($this->getStartDate() <= (new DateTime())) {
        $result->addValidationError(self::START_DATE_FIELD_NAME, esc_html__('Start Date must be in the future', 'sim-league-toolkit'));
      }

      if ($this->getAllowEntryChange() && $this->getEntryChangeLimit() < 1) {
        $result->addValidationError(self::ENTRY_CHANGE_LIMIT_FIELD_NAME, esc_html__('When the championship allows entry changes Entry Change Limit must be at least 1', 'sim-league-toolkit'));
      }

      if ($this->getIsTrackMasterChampionship() && $this->getTrackMasterTrackId() < 1) {
        $result->addValidationError(self::TRACK_ID_FIELD_NAME, esc_html__('When the championship type is track master you must select the track for the championship', 'sim-league-toolkit'));
      }

      if ($this->getIsTrackMasterChampionship() && $this->getTrackMasterTrackLayoutId() < 1) {
        $game = Game::get($this->gameId);
        if ($game->getSupportsLayouts()) {
          $result->addValidationError(self::TRACK_LAYOUT_ID_FIELD_NAME, esc_html__('When the championship type is track master you must select the track layout for the championship', 'sim-league-toolkit'));
        }
      }

      return $result;
    }

    private static function mapChampionships(array $queryResults): array {
      $results = array();

      foreach ($queryResults as $item) {
        $results[] = new Championship($item);
      }

      return $results;
    }
  }