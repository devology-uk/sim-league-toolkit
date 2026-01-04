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
      parent::__construct($data);

      $this->startDate = (new DateTime())->add(new DateInterval('P1D'));
      if ($data != null) {
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
      $queryResults = EventClassesRepository::listForChampionship($this->getId());

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
        if ($this->getId() == Constants::DEFAULT_ID) {
          $this->setId(ChampionshipRepository::add($this->toArray()));
        } else {
          ChampionshipRepository::update($this->getId(), $this->toArray());
        }
      } catch (Exception) {
        return false;
      }

      return true;
    }

    /**
     * @return array{fieldName: string, value: mixed}
     */
    public function toArray(): array {
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

      if ($this->getId() != Constants::DEFAULT_ID) {
        $result['id'] = $this->getId();
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