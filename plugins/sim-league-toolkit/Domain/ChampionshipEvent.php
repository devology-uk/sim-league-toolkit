<?php

  namespace SLTK\Domain;

  use Exception;
  use SLTK\Core\Constants;
  use SLTK\Database\Repositories\ChampionshipEventsRepository;
  use SLTK\Database\Repositories\EventRefsRepository;
  use stdClass;

  class ChampionshipEvent extends EventBase
  {
    private int $championshipId = Constants::DEFAULT_ID;
    private string $championship = '';
    private int $round = 1;

    public function __construct(?stdClass $data = null)
    {
      parent::__construct($data);

      if ($data !== null)
      {
        $this->championshipId = (int)($data->championshipId ?? Constants::DEFAULT_ID);
        $this->championship = $data->championship ?? '';
        $this->round = (int)($data->round ?? 1);
      }
    }

    /**
     * @throws Exception
     */
    public static function get(int $id): ?self
    {
      $row = ChampionshipEventsRepository::getById($id);
      return $row ? new self($row) : null;
    }

    /**
     * @throws Exception
     */
    public static function getByEventRefId(int $eventRefId): ?self
    {
      $row = ChampionshipEventsRepository::getByEventRefId($eventRefId);
      return $row ? new self($row) : null;
    }

    /**
     * @throws Exception
     */
    public static function list(): array
    {
      $rows = ChampionshipEventsRepository::listAll();
      return array_map(fn($row) => new self($row), $rows);
    }

    /**
     * @throws Exception
     */
    public static function listByChampionshipId(int $championshipId): array
    {
      $rows = ChampionshipEventsRepository::listByChampionshipId($championshipId);
      return array_map(fn($row) => new self($row), $rows);
    }

    /**
     * @throws Exception
     */
    public static function delete(int $id): void
    {
      $event = self::get($id);

      if ($event === null)
      {
        return;
      }

      if ($event->hasEventRef())
      {
        EventRefsRepository::delete($event->getEventRefId());
      }
    }

    public function getEventType(): string
    {
      return 'championship';
    }

    public function getChampionshipId(): int { return $this->championshipId; }
    public function setChampionshipId(int $value): void { $this->championshipId = $value; }

    public function getChampionship(): string { return $this->championship; }

    public function getRound(): int { return $this->round; }
    public function setRound(int $value): void { $this->round = $value; }

    public function save(): bool
    {
      try
      {
        if (!$this->hasId())
        {
          $eventRefId = EventRefsRepository::add($this->getEventType());
          $this->setEventRefId($eventRefId);

          $this->setId(ChampionshipEventsRepository::add($this->toArray()));
        }
        else
        {
          ChampionshipEventsRepository::update($this->getId(), $this->toArray());
        }
      }
      catch (Exception)
      {
        return false;
      }

      return true;
    }

    public function toArray(): array
    {
      $result = $this->commonToArray();
      $result['championshipId'] = $this->getChampionshipId();
      $result['round'] = $this->getRound();

      if ($this->hasId())
      {
        $result['id'] = $this->getId();
      }

      return $result;
    }

    public function toDto(): array
    {
      $result = $this->commonToDto();
      $result['eventType'] = $this->getEventType();
      $result['championshipId'] = $this->getChampionshipId();
      $result['championship'] = $this->getChampionship();
      $result['round'] = $this->getRound();

      if ($this->hasId())
      {
        $result['id'] = $this->getId();
      }

      return $result;
    }
  }