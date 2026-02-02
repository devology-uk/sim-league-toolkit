<?php

  namespace SLTK\Domain;

  use Exception;
  use SLTK\Database\Repositories\EventRefsRepository;
  use SLTK\Database\Repositories\StandaloneEventsRepository;
  use SLTK\Domain\Abstractions\AggregateRoot;
  use SLTK\Domain\Abstractions\Deletable;
  use SLTK\Domain\Abstractions\EventBase;
  use SLTK\Domain\Abstractions\Listable;
  use SLTK\Domain\Abstractions\ProvidesPersistableArray;
  use SLTK\Domain\Abstractions\Saveable;
  use stdClass;

  class StandaloneEvent extends EventBase implements AggregateRoot, Deletable, Listable, ProvidesPersistableArray, Saveable {
    private bool $isPublic = true;
    private int $maxEntrants = 0;
    private string $ruleSet = '';
    private ?int $ruleSetId = null;
    private string $scoringSet = '';
    private ?int $scoringSetId = null;


    /**
     * @throws Exception
     */
    public static function delete(int $id): void {
      $event = self::get($id);

      if ($event === null) {
        return;
      }

      if ($event->hasEventRef()) {
        EventRefsRepository::delete($event->getEventRefId());
      }
    }

    public static function fromStdClass(?stdClass $data): ?self {
      if(!$data) {
        return null;
      }

      $result = new self();

      $result->hydrateFromStdClass($data);

      return $result;
    }

    /**
     * @throws Exception
     */
    public static function get(int $id): ?self {
      $row = StandaloneEventsRepository::getById($id);

      return $row ? new self($row) : null;
    }

    /**
     * @throws Exception
     */
    public static function getByEventRefId(int $eventRefId): ?self {
      $row = StandaloneEventsRepository::getByEventRefId($eventRefId);

      return $row ? new self($row) : null;
    }

    /**
     * @throws Exception
     */
    public static function list(): array {
      $rows = StandaloneEventsRepository::listAll();

      return array_map(fn($row) => new self($row), $rows);
    }

    public function getEventType(): string {
      return 'standalone';
    }

    public function getIsPublic(): bool {
      return $this->isPublic;
    }

    public function setIsPublic(bool $value): void {
      $this->isPublic = $value;
    }

    public function getMaxEntrants(): int {
      return $this->maxEntrants;
    }

    public function setMaxEntrants(int $value): void {
      $this->maxEntrants = $value;
    }

    public function getRuleSet(): string {
      return $this->ruleSet;
    }

    public function getRuleSetId(): ?int {
      return $this->ruleSetId;
    }

    public function setRuleSetId(?int $value): void {
      $this->ruleSetId = $value;
    }

    public function getScoringSet(): string {
      return $this->scoringSet;
    }

    public function getScoringSetId(): ?int {
      return $this->scoringSetId;
    }

    public function setScoringSetId(?int $value): void {
      $this->scoringSetId = $value;
    }

    /**
     * @throws Exception
     */
    public function save(): self {
        if (!$this->hasId()) {
          $eventRefId = EventRefsRepository::add($this->getEventType());
          $this->setEventRefId($eventRefId);

          $this->setId(StandaloneEventsRepository::add($this->toArray()));
        } else {
          StandaloneEventsRepository::update($this->getId(), $this->toArray());
        }

        return $this;
    }

    public function toArray(): array {
      $result = $this->commonToArray();
      $result['scoringSetId'] = $this->getScoringSetId();
      $result['ruleSetId'] = $this->getRuleSetId();
      $result['maxEntrants'] = $this->getMaxEntrants();
      $result['isPublic'] = $this->getIsPublic();

      return $result;
    }

    public function toDto(): array {
      $result = $this->commonToDto();
      $result['eventType'] = $this->getEventType();
      $result['scoringSetId'] = $this->getScoringSetId();
      $result['scoringSet'] = $this->getScoringSet();
      $result['ruleSetId'] = $this->getRuleSetId();
      $result['ruleSet'] = $this->getRuleSet();
      $result['maxEntrants'] = $this->getMaxEntrants();
      $result['isPublic'] = $this->getIsPublic();

      return $result;
    }
  }
