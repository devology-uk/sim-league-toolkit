<?php

  namespace SLTK\Domain;

  use DateTime;
  use SLTK\Core\Constants;
  use stdClass;

  abstract class EventBase extends DomainBase
  {
    protected ?int $eventRefId = null;
    protected string $name = '';
    protected string $description = '';
    protected string $bannerImageUrl = '';
    protected int $gameId = Constants::DEFAULT_ID;
    protected string $game = '';
    protected int $trackId = Constants::DEFAULT_ID;
    protected string $track = '';
    protected ?int $trackLayoutId = null;
    protected string $trackLayout = '';
    protected DateTime $eventDate;
    protected bool $isActive = false;

    public function __construct(?stdClass $data = null)
    {
      parent::__construct($data);

      $this->eventDate = new DateTime();

      if ($data !== null)
      {
        $this->hydrateCommon($data);
      }
    }

    protected function hydrateCommon(stdClass $data): void
    {
      $this->eventRefId = isset($data->eventRefId) ? (int)$data->eventRefId : null;
      $this->name = $data->name ?? '';
      $this->description = $data->description ?? '';
      $this->bannerImageUrl = $data->bannerImageUrl ?? '';
      $this->gameId = (int)($data->gameId ?? Constants::DEFAULT_ID);
      $this->game = $data->game ?? '';
      $this->trackId = (int)($data->trackId ?? Constants::DEFAULT_ID);
      $this->track = $data->track ?? '';
      $this->trackLayoutId = isset($data->trackLayoutId) ? (int)$data->trackLayoutId : null;
      $this->trackLayout = $data->trackLayout ?? '';
      $this->eventDate = isset($data->eventDate)
        ? DateTime::createFromFormat(Constants::STANDARD_DATE_FORMAT, $data->eventDate)
        : new DateTime();
      $this->isActive = (bool)($data->isActive ?? false);
    }

    protected function commonToArray(): array
    {
      return [
        'eventRefId' => $this->getEventRefId(),
        'name' => $this->getName(),
        'description' => $this->getDescription(),
        'bannerImageUrl' => $this->getBannerImageUrl(),
        'gameId' => $this->getGameId(),
        'trackId' => $this->getTrackId(),
        'trackLayoutId' => $this->getTrackLayoutId(),
        'eventDate' => $this->getFormattedEventDate(),
        'isActive' => $this->getIsActive(),
      ];
    }

    protected function commonToDto(): array
    {
      return [
        'eventRefId' => $this->getEventRefId(),
        'name' => $this->getName(),
        'description' => $this->getDescription(),
        'bannerImageUrl' => $this->getBannerImageUrl(),
        'gameId' => $this->getGameId(),
        'game' => $this->getGame(),
        'trackId' => $this->getTrackId(),
        'track' => $this->getTrack(),
        'trackLayoutId' => $this->getTrackLayoutId(),
        'trackLayout' => $this->getTrackLayout(),
        'eventDate' => $this->getFormattedEventDate(),
        'isActive' => $this->getIsActive(),
      ];
    }

    public function getEventRefId(): ?int
    {
      return $this->eventRefId;
    }

    public function setEventRefId(int $value): void
    {
      $this->eventRefId = $value;
    }

    public function hasEventRef(): bool
    {
      return $this->eventRefId !== null && $this->eventRefId !== Constants::DEFAULT_ID;
    }

    public function getName(): string { return $this->name; }
    public function setName(string $value): void { $this->name = trim($value); }

    public function getDescription(): string { return $this->description; }
    public function setDescription(string $value): void { $this->description = trim($value); }

    public function getBannerImageUrl(): string { return $this->bannerImageUrl; }
    public function setBannerImageUrl(string $value): void { $this->bannerImageUrl = $value; }

    public function getGameId(): int { return $this->gameId; }
    public function setGameId(int $value): void { $this->gameId = $value; }

    public function getGame(): string { return $this->game; }

    public function getTrackId(): int { return $this->trackId; }
    public function setTrackId(int $value): void { $this->trackId = $value; }

    public function getTrack(): string { return $this->track; }

    public function getTrackLayoutId(): ?int { return $this->trackLayoutId; }
    public function setTrackLayoutId(?int $value): void { $this->trackLayoutId = $value; }

    public function getTrackLayout(): string { return $this->trackLayout; }

    public function getEventDate(): DateTime { return $this->eventDate; }
    public function setEventDate(DateTime $value): void { $this->eventDate = $value; }

    public function getFormattedEventDate(): string
    {
      return $this->eventDate->format(Constants::STANDARD_DATE_FORMAT);
    }

    public function getIsActive(): bool { return $this->isActive; }
    public function setIsActive(bool $value): void { $this->isActive = $value; }

    public function getSessions(): array
    {
      if (!$this->hasEventRef())
      {
        return [];
      }

      return EventSession::listByEventRefId($this->getEventRefId());
    }

    abstract public function getEventType(): string;
  }
