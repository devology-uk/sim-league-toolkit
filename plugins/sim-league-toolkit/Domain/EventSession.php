<?php

  namespace SLTK\Domain;

  use SLTK\Config\GameConfigProvider;
  use SLTK\Core\Constants;
  use SLTK\Database\Repositories\EventSessionAttributesRepository;
  use SLTK\Database\Repositories\EventSessionRepository;
  use stdClass;

  class EventSession extends DomainBase
  {
    private int $eventRefId = Constants::DEFAULT_ID;
    private string $name = '';
    private string $sessionType = '';
    private string $startTime = '08:00';
    private int $duration = 15;
    private int $sortOrder = 0;
    private array $attributes = [];

    private string $eventType = '';
    private string $gameId = '';

    public function __construct(?stdClass $data = null)
    {
      parent::__construct($data);

      if ($data !== null)
      {
        $this->eventRefId = (int)($data->eventRefId ?? Constants::DEFAULT_ID);
        $this->name = $data->name ?? '';
        $this->sessionType = $data->sessionType ?? '';
        $this->startTime = $data->startTime ?? '08:00';
        $this->duration = (int)($data->duration ?? 15);
        $this->sortOrder = (int)($data->sortOrder ?? 0);
        $this->attributes = $this->hydrateAttributes($data->attributes ?? []);
        $this->eventType = $data->eventType ?? '';
        $this->gameId = $data->gameId ?? '';
      }
    }

    private function hydrateAttributes(array|object $attributes): array
    {
      if (is_object($attributes))
      {
        $attributes = (array)$attributes;
      }

      return $attributes;
    }

    public static function get(int $id): ?self
    {
      $row = EventSessionRepository::getById($id);
      return $row ? new self($row) : null;
    }

    public static function list(): array
    {
      $rows = EventSessionRepository::listAll();
      return array_map(fn($row) => new self($row), $rows);
    }

    public static function listByEventRefId(int $eventRefId): array
    {
      $rows = EventSessionRepository::listByEventRefId($eventRefId);
      return array_map(fn($row) => new self($row), $rows);
    }

    /**
     * @throws \Exception
     */
    public static function delete(int $id): void
    {
      EventSessionRepository::delete($id);
    }

    /**
     * @throws \Exception
     */
    public static function reorder(int $eventRefId, array $sessionIds): void
    {
      foreach ($sessionIds as $index => $sessionId)
      {
        EventSessionRepository::updateSortOrder((int)$sessionId, $index);
      }
    }

    public function getEventRefId(): int { return $this->eventRefId; }
    public function setEventRefId(int $value): void { $this->eventRefId = $value; }

    public function getName(): string { return $this->name; }
    public function setName(string $value): void { $this->name = trim($value); }

    public function getSessionType(): string { return $this->sessionType; }
    public function setSessionType(string $value): void { $this->sessionType = $value; }

    public function getStartTime(): string { return $this->startTime; }
    public function setStartTime(string $value): void { $this->startTime = $value; }

    public function getDuration(): int { return $this->duration; }
    public function setDuration(int $value): void { $this->duration = $value; }

    public function getSortOrder(): int { return $this->sortOrder; }
    public function setSortOrder(int $value): void { $this->sortOrder = $value; }

    public function getAttributes(): array { return $this->attributes; }
    public function setAttributes(array $value): void { $this->attributes = $value; }

    public function getAttribute(string $key): mixed
    {
      return $this->attributes[$key] ?? null;
    }

    public function setAttribute(string $key, mixed $value): void
    {
      $this->attributes[$key] = $value;
    }

    public function removeAttribute(string $key): void
    {
      unset($this->attributes[$key]);
    }

    public function getEventType(): string { return $this->eventType; }

    public function getGameId(): string { return $this->gameId; }
    public function setGameId(string $value): void { $this->gameId = $value; }

    public function save(): bool
    {
      try
      {
        $this->validateAndNormalizeAttributes();

        $data = [
          'eventRefId' => $this->eventRefId,
          'name' => $this->name,
          'sessionType' => $this->sessionType,
          'startTime' => $this->startTime,
          'duration' => $this->duration,
          'sortOrder' => $this->sortOrder,
          'attributes' => $this->attributes,
        ];

        if (!$this->hasId())
        {
          $this->setId(EventSessionRepository::add($data));
        }
        else
        {
          EventSessionRepository::update($this->getId(), $data);
        }
      }
      catch (\Exception)
      {
        return false;
      }

      return true;
    }

    private function validateAndNormalizeAttributes(): void
    {
      if (empty($this->gameId))
      {
        return;
      }

      try
      {
        $config = GameConfigProvider::load($this->gameId);
      }
      catch (\Exception)
      {
        return;
      }

      $sessionTypeConfig = $config->sessionTypes->{$this->sessionType} ?? null;

      if ($sessionTypeConfig === null)
      {
        return;
      }

      $fields = $sessionTypeConfig->fields ?? [];
      $normalized = [];

      foreach ($fields as $field)
      {
        $key = $field->key;
        $value = $this->attributes[$key] ?? $field->default ?? null;

        $normalized[$key] = match ($field->type)
        {
          'boolean' => (bool)$value,
          'number' => $this->clampNumber($value, $field),
          'select' => $this->validateSelectOption($value, $field),
          default => sanitize_text_field($value ?? '')
        };
      }

      $this->attributes = $normalized;
    }

    private function clampNumber($value, object $field): int
    {
      $num = (int)$value;
      $min = $field->validation->min ?? PHP_INT_MIN;
      $max = $field->validation->max ?? PHP_INT_MAX;

      return max($min, min($max, $num));
    }

    private function validateSelectOption($value, object $field): ?string
    {
      $validOptions = array_column($field->options ?? [], 'value');

      if (in_array($value, $validOptions))
      {
        return $value;
      }

      return $field->default ?? $validOptions[0] ?? null;
    }

    public function toArray(): array
    {
      $result = [
        'eventRefId' => $this->getEventRefId(),
        'name' => $this->getName(),
        'sessionType' => $this->getSessionType(),
        'startTime' => $this->getStartTime(),
        'duration' => $this->getDuration(),
        'sortOrder' => $this->getSortOrder(),
      ];

      if ($this->hasId())
      {
        $result['id'] = $this->getId();
      }

      return $result;
    }

    public function toDto(): array
    {
      $result = [
        'eventRefId' => $this->getEventRefId(),
        'eventType' => $this->getEventType(),
        'name' => $this->getName(),
        'sessionType' => $this->getSessionType(),
        'startTime' => $this->getStartTime(),
        'duration' => $this->getDuration(),
        'sortOrder' => $this->getSortOrder(),
        'attributes' => $this->getAttributes(),
      ];

      if ($this->hasId())
      {
        $result['id'] = $this->getId();
      }

      return $result;
    }
  }