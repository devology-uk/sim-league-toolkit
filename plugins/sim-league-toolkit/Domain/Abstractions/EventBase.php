<?php

  namespace SLTK\Domain\Abstractions;

  use DateTime;
  use SLTK\Core\Constants;
  use SLTK\Domain\Traits\HasIdentity;
  use stdClass;

  abstract class EventBase {
    use HasIdentity;

    private string $bannerImageUrl = '';
    private string $description = '';
    private DateTime $eventDate;
    private ?int $eventRefId = null;
    private string $game = '';
    private int $gameId = Constants::DEFAULT_ID;
    private bool $isActive = false;
    private string $name = '';
    private string $track = '';
    private int $trackId = Constants::DEFAULT_ID;
    private string $trackLayout = '';
    private ?int $trackLayoutId = null;

    public function getBannerImageUrl(): string {
      return $this->bannerImageUrl;
    }

    public function setBannerImageUrl(string $value): void {
      $this->bannerImageUrl = $value;
    }

    public function getDescription(): string {
      return $this->description;
    }

    public function setDescription(string $value): void {
      $this->description = trim($value);
    }

    public function getEventDate(): DateTime {
      return $this->eventDate;
    }

    public function setEventDate(DateTime $value): void {
      $this->eventDate = $value;
    }

    public function getEventRefId(): ?int {
      return $this->eventRefId;
    }

    public function setEventRefId(int $value): void {
      $this->eventRefId = $value;
    }

    abstract public function getEventType(): string;

    public function getFormattedEventDate(): string {
      return $this->eventDate->format(Constants::STANDARD_DATE_FORMAT);
    }

    public function getGame(): string {
      return $this->game;
    }

    private function setGame(string $value): void {
      $this->game = $value;
    }

    public function getGameId(): int {
      return $this->gameId;
    }

    public function setGameId(int $value): void {
      $this->gameId = $value;
    }

    public function getIsActive(): bool {
      return $this->isActive;
    }

    public function setIsActive(bool $value): void {
      $this->isActive = $value;
    }

    public function getName(): string {
      return $this->name;
    }

    public function setName(string $value): void {
      $this->name = trim($value);
    }

    public function getTrack(): string {
      return $this->track;
    }

    private function setTrack(string $value): void {
      $this->track = trim($value);
    }

    public function getTrackId(): int {
      return $this->trackId;
    }

    public function setTrackId(int $value): void {
      $this->trackId = $value;
    }

    public function getTrackLayout(): string {
      return $this->trackLayout;
    }

    private function setTrackLayout(string $value): void {
      $this->trackLayout = trim($value);
    }

    public function getTrackLayoutId(): ?int {
      return $this->trackLayoutId;
    }

    public function setTrackLayoutId(?int $value): void {
      $this->trackLayoutId = $value;
    }

    public function hasEventRef(): bool {
      return $this->eventRefId !== null && $this->eventRefId !== Constants::DEFAULT_ID;
    }

    protected function commonToArray(): array {
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

    protected function commonToDto(): array {
      return [
        'id' => $this->getId(),
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

    protected function hydrateFromStdClass(stdClass $data): void {
      $this->setEventRefId(isset($data->eventRefId) ? (int)$data->eventRefId : null);
      $this->setName($data->name ?? '');
      $this->setDescription($data->description ?? '');
      $this->setBannerImageUrl($data->bannerImageUrl ?? '');
      $this->setGameId((int)($data->gameId ?? Constants::DEFAULT_ID));
      $this->setGame($data->game ?? '');
      $this->setTrackId((int)($data->trackId ?? Constants::DEFAULT_ID));
      $this->setTrack($data->track ?? '');
      $this->setTrackLayoutId(isset($data->trackLayoutId) ? (int)$data->trackLayoutId : null);
      $this->setTrackLayout($data->trackLayout ?? '');
      $this->setEventDate(isset($data->eventDate)
        ? DateTime::createFromFormat(Constants::STANDARD_DATE_FORMAT, $data->eventDate)
        : new DateTime());
      $this->setIsActive((bool)($data->isActive ?? false));
    }
  }
