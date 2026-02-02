<?php

  namespace SLTK\Domain;

  use Exception;
  use SLTK\Config\GameConfigProvider;
  use SLTK\Core\Constants;
  use SLTK\Domain\Abstractions\ProvidesPersistableArray;
  use SLTK\Domain\Abstractions\ValueObject;
  use SLTK\Domain\Traits\HasIdentity;
  use stdClass;

  class EventSession implements ValueObject, ProvidesPersistableArray {
    use HasIdentity;

    private array $attributes = [];
    private int $duration = 15;
    private int $eventRefId = Constants::DEFAULT_ID;
    private string $eventType = '';
    private string $gameId = '';
    private string $name = '';
    private string $sessionType = '';
    private int $sortOrder = 0;
    private string $startTime = '08:00';

    public static function fromStdClass(?stdClass $data): ?self {
      if(!$data) {
        return null;
      }

      $result = new self();

      $result->setEventRefId($data->eventRefId ?? Constants::DEFAULT_ID);
      $result->setName($data->name ?? '');
      $result->setSessionType($data->sessionType ?? '');
      $result->setStartTime($data->startTime ?? '');
      $result->setDuration($data->duration ?? 15);
      $result->setSortOrder($data->order ?? 0);
      $result->setGameId($data->gameId ?? '');
      $result->setEventType($data->eventType ?? '');
      $result->setAttributes($result->hydrateAttributes($data->attributes ?? []));

      return $result;
    }

    public function getAttribute(string $key): mixed {
      return $this->attributes[$key] ?? null;
    }

    public function getAttributes(): array {
      return $this->attributes;
    }

    public function setAttributes(array $value): void {
      $this->attributes = $value;
    }

    public function getDuration(): int {
      return $this->duration;
    }

    public function setDuration(int $value): void {
      $this->duration = $value;
    }

    public function getEventRefId(): int {
      return $this->eventRefId;
    }

    public function setEventRefId(int $value): void {
      $this->eventRefId = $value;
    }

    public function getEventType(): string {
      return $this->eventType;
    }

    public function getGameId(): string {
      return $this->gameId;
    }

    public function setGameId(string $value): void {
      $this->gameId = $value;
    }

    public function getName(): string {
      return $this->name;
    }

    public function setName(string $value): void {
      $this->name = trim($value);
    }

    public function getSessionType(): string {
      return $this->sessionType;
    }

    public function setSessionType(string $value): void {
      $this->sessionType = $value;
    }

    public function getSortOrder(): int {
      return $this->sortOrder;
    }

    public function setSortOrder(int $value): void {
      $this->sortOrder = $value;
    }

    public function getStartTime(): string {
      return $this->startTime;
    }

    public function setStartTime(string $value): void {
      $this->startTime = $value;
    }

    public function removeAttribute(string $key): void {
      unset($this->attributes[$key]);
    }

    public function setAttribute(string $key, mixed $value): void {
      $this->attributes[$key] = $value;
    }

    public function toArray(): array {
      return [
        'eventRefId' => $this->getEventRefId(),
        'name' => $this->getName(),
        'sessionType' => $this->getSessionType(),
        'startTime' => $this->getStartTime(),
        'duration' => $this->getDuration(),
        'sortOrder' => $this->getSortOrder(),
      ];
    }

    public function toDto(): array {
      return [
        'id' => $this->getId(),
        'eventRefId' => $this->getEventRefId(),
        'eventType' => $this->getEventType(),
        'name' => $this->getName(),
        'sessionType' => $this->getSessionType(),
        'startTime' => $this->getStartTime(),
        'duration' => $this->getDuration(),
        'sortOrder' => $this->getSortOrder(),
        'attributes' => $this->getAttributes(),
      ];
    }

    private function clampNumber($value, object $field): int {
      $num = (int)$value;
      $min = $field->validation->min ?? PHP_INT_MIN;
      $max = $field->validation->max ?? PHP_INT_MAX;

      return max($min, min($max, $num));
    }

    private function hydrateAttributes(array|object $attributes): array {
      if (is_object($attributes)) {
        $attributes = (array)$attributes;
      }

      return $attributes;
    }

    private function setEventType(string $eventType): void {
      $this->eventType = $eventType;
    }

    private function validateAndNormalizeAttributes(): void {
      if (empty($this->gameId)) {
        return;
      }

      try {
        $config = GameConfigProvider::load($this->gameId);
      } catch (Exception) {
        return;
      }

      $sessionTypeConfig = $config->sessionTypes->{$this->sessionType} ?? null;

      if ($sessionTypeConfig === null) {
        return;
      }

      $fields = $sessionTypeConfig->fields ?? [];
      $normalized = [];

      foreach ($fields as $field) {
        $key = $field->key;
        $value = $this->attributes[$key] ?? $field->default ?? null;

        $normalized[$key] = match ($field->type) {
          'boolean' => (bool)$value,
          'number' => $this->clampNumber($value, $field),
          'select' => $this->validateSelectOption($value, $field),
          default => sanitize_text_field($value ?? '')
        };
      }

      $this->attributes = $normalized;
    }

    private function validateSelectOption($value, object $field): ?string {
      $validOptions = array_column($field->options ?? [], 'value');

      if (in_array($value, $validOptions)) {
        return $value;
      }

      return $field->default ?? $validOptions[0] ?? null;
    }
  }