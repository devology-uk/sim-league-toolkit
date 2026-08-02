<?php

  namespace SLTK\Domain;

  use Exception;
  use SLTK\Core\Constants;
  use SLTK\Database\Repositories\StandaloneSessionResultsRepository;
  use SLTK\Domain\Abstractions\AggregateRoot;
  use SLTK\Domain\Abstractions\Deletable;
  use SLTK\Domain\Abstractions\ProvidesPersistableArray;
  use SLTK\Domain\Abstractions\Saveable;
  use SLTK\Domain\Traits\HasIdentity;
  use SLTK\Domain\Traits\HasSessionResultFields;
  use stdClass;

  class StandaloneSessionResult implements AggregateRoot, Deletable, ProvidesPersistableArray, Saveable {
    use HasIdentity;
    use HasSessionResultFields;

    private int $eventSessionId = Constants::DEFAULT_ID;
    private int $standaloneEventEntryId = Constants::DEFAULT_ID;
    private int $userId = Constants::DEFAULT_ID;
    private string $memberName = '';
    private int $raceNumber = 0;
    private ?int $eventClassId = null;
    private ?string $className = null;

    /**
     * @throws Exception
     */
    public static function delete(int $id): void {
      StandaloneSessionResultsRepository::delete($id);
    }

    public static function fromStdClass(?stdClass $data): ?self {
      if (!$data) {
        return null;
      }

      $result = new self();
      $result->setId((int)$data->id);
      $result->setEventSessionId((int)$data->eventSessionId);
      $result->setStandaloneEventEntryId((int)$data->standaloneEventEntryId);
      $result->setUserId((int)($data->userId ?? Constants::DEFAULT_ID));
      $result->setMemberName($data->memberName ?? '');
      $result->setRaceNumber((int)($data->raceNumber ?? 0));
      $result->setEventClassId(isset($data->eventClassId) && $data->eventClassId > 0 ? (int)$data->eventClassId : null);
      $result->setClassName($data->className ?? null);
      $result->hydrateSessionResultFields($data);

      return $result;
    }

    /**
     * @throws Exception
     */
    public static function get(int $id): ?self {
      return self::fromStdClass(StandaloneSessionResultsRepository::getById($id));
    }

    /**
     * @return StandaloneSessionResult[]
     * @throws Exception
     */
    public static function listByEventSession(int $eventSessionId): array {
      $results = StandaloneSessionResultsRepository::listByEventSessionId($eventSessionId);

      return array_map(fn($row) => self::fromStdClass($row), $results);
    }

    public function getEventSessionId(): int {
      return $this->eventSessionId;
    }

    public function setEventSessionId(int $value): void {
      $this->eventSessionId = $value;
    }

    public function getStandaloneEventEntryId(): int {
      return $this->standaloneEventEntryId;
    }

    public function setStandaloneEventEntryId(int $value): void {
      $this->standaloneEventEntryId = $value;
    }

    public function getUserId(): int {
      return $this->userId;
    }

    private function setUserId(int $value): void {
      $this->userId = $value;
    }

    public function getMemberName(): string {
      return $this->memberName;
    }

    private function setMemberName(string $value): void {
      $this->memberName = $value;
    }

    public function getRaceNumber(): int {
      return $this->raceNumber;
    }

    private function setRaceNumber(int $value): void {
      $this->raceNumber = $value;
    }

    public function getEventClassId(): ?int {
      return $this->eventClassId;
    }

    private function setEventClassId(?int $value): void {
      $this->eventClassId = $value;
    }

    public function getClassName(): ?string {
      return $this->className;
    }

    private function setClassName(?string $value): void {
      $this->className = $value;
    }

    /**
     * @throws Exception
     */
    public function save(): self {
      if (!$this->hasId()) {
        $this->setId(StandaloneSessionResultsRepository::add($this->toArray()));
      } else {
        StandaloneSessionResultsRepository::update($this->getId(), $this->toArray());
      }

      return $this;
    }

    public function toArray(): array {
      return array_merge(
        [
          'eventSessionId' => $this->getEventSessionId(),
          'standaloneEventEntryId' => $this->getStandaloneEventEntryId(),
        ],
        $this->sessionResultFieldsToArray()
      );
    }

    public function toDto(): array {
      return array_merge(
        [
          'id' => $this->getId(),
          'eventSessionId' => $this->getEventSessionId(),
          'standaloneEventEntryId' => $this->getStandaloneEventEntryId(),
          'userId' => $this->getUserId(),
          'memberName' => $this->getMemberName(),
          'raceNumber' => $this->getRaceNumber(),
          'eventClassId' => $this->getEventClassId(),
          'className' => $this->getClassName(),
        ],
        $this->sessionResultFieldsToArray()
      );
    }
  }
