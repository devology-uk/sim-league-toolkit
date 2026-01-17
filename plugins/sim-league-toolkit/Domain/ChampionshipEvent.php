<?php

  namespace SLTK\Domain;

  use DateMalformedStringException;
  use DateTime;
  use DateTimeZone;
  use Exception;
  use SLTK\Core\Constants;
  use SLTK\Database\Repositories\ChampionshipEventsRepository;
  use stdClass;

  class ChampionshipEvent extends EntityBase {
    private string $bannerImageUrl;
    private string $championship;
    private int $championshipId;
    private string $description;
    private bool $isActive;
    private bool $isCompleted;
    private string $name;
    private string $ruleSet;
    private int $ruleSetId;
    private DateTime $startDateTime;
    private string $track;
    private int $trackId;
    private ?string $trackLayout;
    private ?int $trackLayoutId;

    public function __construct(?stdClass $data = null) {
      parent::__construct($data);
      if ($data !== null) {
        $this->bannerImageUrl = $data->bannerImageUrl ?? '';
        $this->championship = $data->championship ?? '';
        $this->championshipId = $data->championshipId;
        $this->description = $data->description ?? '';
        $this->isActive = $data->isActive ?? false;
        $this->isCompleted = $data->isCompleted ?? false;
        $this->name = $data->name ?? '';
        $this->ruleSet = $data->ruleSet ?? '';
        $this->ruleSetId = $data->ruleSetId;
        $this->startDateTime = DateTime::createFromFormat('Y-m-d H:i:s', $data->startDateTime);
        $this->track = $data->track ?? '';
        $this->trackId = $data->trackId;
        $this->trackLayout = $data->trackLayout ?? null;
        $this->trackLayoutId = $data->trackLayoutId ?? null;
      }
    }

    /**
     * @throws Exception
     */
    public static function delete(int $id): void {
      ChampionshipEventsRepository::delete($id);
    }

    /**
     * @throws Exception
     */
    public static function get(int $id): ChampionshipEvent|null {
      $queryResult = ChampionshipEventsRepository::getById($id);

      return new ChampionshipEvent($queryResult);
    }

    /**
     * @return ChampionshipEvent[]
     * @throws Exception
     */
    public static function list(int $championshipId): array {
      $queryResults = ChampionshipEventsRepository::list($championshipId);

      return self::mapChampionshipEvents($queryResults);
    }

    public function getBannerImageUrl(): string {
      return $this->bannerImageUrl ?? '';
    }

    public function setBannerImageUrl(string $value): void {
      $this->bannerImageUrl = $value;
    }

    public function getChampionship(): string {
      return $this->championship ?? '';
    }

    public function setChampionship(string $value): void {
      $this->championship = $value;
    }

    public function getChampionshipId(): int {
      return $this->championshipId ?? Constants::DEFAULT_ID;
    }

    public function setChampionshipId(int $value): void {
      $this->championshipId = $value;
    }

    public function getDescription(): string {
      return $this->description ?? '';
    }

    public function setDescription(string $value): void {
      $this->description = $value;
    }

    public function getName(): string {
      return $this->name ?? '';
    }

    public function setName(string $value): void {
      $this->name = $value;
    }

    public function getRuleSet(): string {
      return $this->ruleSet ?? '';
    }

    public function setRuleSet(string $value): void {
      $this->ruleSet = $value;
    }

    public function getRuleSetId(): int {
      return $this->ruleSetId ?? Constants::DEFAULT_ID;
    }

    public function setRuleSetId(int $value): void {
      $this->ruleSetId = $value;
    }

    public function getStartDateTime(): DateTime {
      return $this->startDateTime ?? new DateTime();
    }

    public function setStartDateTime(DateTime $value): void {
      $this->startDateTime = $value;
    }

    public function getTrack(): string {
      return $this->track ?? '';
    }

    public function setTrack(string $value): void {
      $this->track = $value;
    }

    public function getTrackId(): int {
      return $this->trackId ?? Constants::DEFAULT_ID;
    }

    public function setTrackId(int $value): void {
      $this->trackId = $value;
    }

    public function getTrackLayout(): string {
      return $this->trackLayout ?? '';
    }

    public function setTrackLayout(string $value): void {
      $this->trackLayout = $value;
    }

    public function getTrackLayoutId(): ?int {
      return $this->trackLayoutId ?? null;
    }

    public function setTrackLayoutId(int $value): void {
      $this->trackLayoutId = $value;
    }

    public function isActive(): bool {
      return $this->isActive ?? false;
    }

    public function isCompleted(): bool {
      return $this->isCompleted ?? false;
    }

    public function setActive(bool $value): void {
      $this->isActive = $value;
    }

    public function setCompleted(bool $value): void {
      $this->isCompleted = $value;
    }

    public function toArray(): array {
      $result = [
        'bannerImageUrl' => $this->getBannerImageUrl(),
        'championshipId' => $this->getChampionshipId(),
        'description' => $this->getDescription(),
        'isActive' => $this->isActive(),
        'name' => $this->getName(),
        'ruleSetId' => $this->getRuleSetId(),
        'startDateTime' => $this->getStartDateTime()->format('Y-m-d H:i:s'),
        'trackId' => $this->getTrackId(),
      ];

      if($this->getTrackLayoutId() !== null) {
        $result['trackLayoutId'] = $this->getTrackLayoutId();
      }

      if ($this->hasId()) {
        $result["id"] = $this->getId();
      }

      return $result;
    }

    public function toDto(): array {
      $result = [
        'id' => $this->getId(),
        'bannerImageUrl' => $this->getBannerImageUrl(),
        'championship' => $this->getChampionship(),
        'championshipId' => $this->getChampionshipId(),
        'description' => $this->getDescription(),
        'isActive' => $this->isActive(),
        'isCompleted' => $this->isCompleted(),
        'name' => $this->getName(),
        'ruleSet' => $this->getRuleSet(),
        'ruleSetId' => $this->getRuleSetId(),
        'startDateTime' => $this->getStartDateTime()->format('Y-m-d H:i'),
        'track' => $this->getTrack(),
        'trackId' => $this->getTrackId(),
        'trackLayout' => $this->getTrackLayout(),
      ];

      if($this->getTrackLayoutId() !== null) {
        $result['trackLayoutId'] = $this->getTrackLayoutId();
      }

      return $result;
    }

    private static function mapChampionshipEvents(array $queryResults): array {
      $results = array();

      foreach ($queryResults as $item) {
        $results[] = new ChampionshipEvent($item);
      }

      return $results;
    }



    public function save(): bool {
      try {
        if (!$this->hasId()) {
          $this->setId(ChampionshipEventsRepository::add($this->toArray()));
        } else {
          ChampionshipEventsRepository::update($this->getId(), $this->toArray());
        }
      } catch (Exception) {
        return false;
      }

      return true;
    }
  }