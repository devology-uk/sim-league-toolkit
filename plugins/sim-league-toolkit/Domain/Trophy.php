<?php

  namespace SLTK\Domain;

  use DateTime;
  use DateTimeInterface;
  use DateTimeZone;
  use Exception;
  use SLTK\Core\Constants;
  use SLTK\Core\Enums\TrophyAwardType;
  use SLTK\Core\Enums\TrophyScope;
  use SLTK\Database\Repositories\TrophiesRepository;
  use SLTK\Domain\Abstractions\AggregateRoot;
  use SLTK\Domain\Abstractions\Deletable;
  use SLTK\Domain\Abstractions\ProvidesPersistableArray;
  use SLTK\Domain\Abstractions\Saveable;
  use SLTK\Domain\Traits\HasIdentity;
  use stdClass;

  class Trophy implements AggregateRoot, Deletable, ProvidesPersistableArray, Saveable {
    use HasIdentity;

    private int $memberId = Constants::DEFAULT_ID;
    private TrophyScope $scope;
    private int $scopeId = Constants::DEFAULT_ID;
    private ?int $eventSessionId = null;
    private ?int $eventClassId = null;
    private TrophyAwardType $awardType;
    private DateTime $awardedDate;
    private string $memberName = '';
    private ?string $className = null;
    private ?string $sessionName = null;

    public function __construct() {
      $this->awardedDate = new DateTime('now', new DateTimeZone('UTC'));
    }

    /**
     * @throws Exception
     */
    public static function delete(int $id): void {
      TrophiesRepository::delete($id);
    }

    /**
     * @throws Exception
     */
    public static function deleteByScope(TrophyScope $scope, int $scopeId): void {
      TrophiesRepository::deleteByScope($scope->value, $scopeId);
    }

    public static function fromStdClass(?stdClass $data): ?self {
      if (!$data) {
        return null;
      }

      $result = new self();
      $result->setId((int)$data->id);
      $result->setMemberId((int)$data->memberId);
      $result->setScope(TrophyScope::from($data->scope));
      $result->setScopeId((int)$data->scopeId);
      $result->setEventSessionId(isset($data->eventSessionId) && $data->eventSessionId > 0 ? (int)$data->eventSessionId : null);
      $result->setEventClassId(isset($data->eventClassId) && $data->eventClassId > 0 ? (int)$data->eventClassId : null);
      $result->setAwardType(TrophyAwardType::from($data->awardType));
      $result->setAwardedDate(DateTime::createFromFormat('Y-m-d H:i:s', $data->awardedDate, new DateTimeZone('UTC')) ?: new DateTime('now', new DateTimeZone('UTC')));
      $result->setMemberName($data->memberName ?? '');
      $result->setClassName($data->className ?? null);
      $result->setSessionName($data->sessionName ?? null);

      return $result;
    }

    /**
     * @throws Exception
     */
    public static function get(int $id): ?self {
      return self::fromStdClass(TrophiesRepository::getById($id));
    }

    /**
     * @return Trophy[]
     * @throws Exception
     */
    public static function listByScope(TrophyScope $scope, int $scopeId): array {
      $results = TrophiesRepository::listByScope($scope->value, $scopeId);

      return array_map(fn($row) => self::fromStdClass($row), $results);
    }

    /**
     * @return Trophy[]
     * @throws Exception
     */
    public static function listByMemberId(int $memberId): array {
      $results = TrophiesRepository::listByMemberId($memberId);

      return array_map(fn($row) => self::fromStdClass($row), $results);
    }

    public function getMemberId(): int {
      return $this->memberId;
    }

    public function setMemberId(int $value): void {
      $this->memberId = $value;
    }

    public function getScope(): TrophyScope {
      return $this->scope;
    }

    public function setScope(TrophyScope $value): void {
      $this->scope = $value;
    }

    public function getScopeId(): int {
      return $this->scopeId;
    }

    public function setScopeId(int $value): void {
      $this->scopeId = $value;
    }

    public function getEventSessionId(): ?int {
      return $this->eventSessionId;
    }

    public function setEventSessionId(?int $value): void {
      $this->eventSessionId = $value;
    }

    public function getEventClassId(): ?int {
      return $this->eventClassId;
    }

    public function setEventClassId(?int $value): void {
      $this->eventClassId = $value;
    }

    public function getAwardType(): TrophyAwardType {
      return $this->awardType;
    }

    public function setAwardType(TrophyAwardType $value): void {
      $this->awardType = $value;
    }

    public function getAwardedDate(): DateTime {
      return $this->awardedDate;
    }

    public function setAwardedDate(DateTime $value): void {
      $this->awardedDate = $value;
    }

    public function getMemberName(): string {
      return $this->memberName;
    }

    private function setMemberName(string $value): void {
      $this->memberName = $value;
    }

    public function getClassName(): ?string {
      return $this->className;
    }

    private function setClassName(?string $value): void {
      $this->className = $value;
    }

    public function getSessionName(): ?string {
      return $this->sessionName;
    }

    private function setSessionName(?string $value): void {
      $this->sessionName = $value;
    }

    /**
     * @throws Exception
     */
    public function save(): self {
      if (!$this->hasId()) {
        $this->setId(TrophiesRepository::add($this->toArray()));
      }

      return $this;
    }

    public function toArray(): array {
      return [
        'memberId' => $this->getMemberId(),
        'scope' => $this->getScope()->value,
        'scopeId' => $this->getScopeId(),
        'eventSessionId' => $this->getEventSessionId(),
        'eventClassId' => $this->getEventClassId(),
        'awardType' => $this->getAwardType()->value,
        'awardedDate' => $this->getAwardedDate()->format('Y-m-d H:i:s'),
      ];
    }

    public function toDto(): array {
      return [
        'id' => $this->getId(),
        'memberId' => $this->getMemberId(),
        'scope' => $this->getScope()->value,
        'scopeId' => $this->getScopeId(),
        'eventSessionId' => $this->getEventSessionId(),
        'eventClassId' => $this->getEventClassId(),
        'awardType' => $this->getAwardType()->value,
        'awardedDate' => $this->getAwardedDate()->format(DateTimeInterface::RFC3339_EXTENDED),
        'memberName' => $this->getMemberName(),
        'className' => $this->getClassName(),
        'sessionName' => $this->getSessionName(),
      ];
    }
  }
