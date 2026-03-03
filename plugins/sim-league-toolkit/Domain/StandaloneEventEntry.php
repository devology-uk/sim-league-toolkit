<?php

namespace SLTK\Domain;

use Exception;
use SLTK\Core\Constants;
use SLTK\Database\Repositories\StandaloneEventEntriesRepository;
use SLTK\Domain\Abstractions\AggregateRoot;
use SLTK\Domain\Abstractions\Deletable;
use SLTK\Domain\Abstractions\ProvidesPersistableArray;
use SLTK\Domain\Abstractions\Saveable;
use SLTK\Domain\Traits\HasIdentity;
use stdClass;

class StandaloneEventEntry implements AggregateRoot, Deletable, ProvidesPersistableArray, Saveable {
    use HasIdentity;

    private int $standaloneEventId = Constants::DEFAULT_ID;
    private ?int $eventClassId = null;
    private int $carId = Constants::DEFAULT_ID;
    private int $userId = Constants::DEFAULT_ID;
    private string $memberName = '';
    private string $firstName = '';
    private string $lastName = '';
    private int $raceNumber = 0;
    private string $avatarUrl = '';
    private string $carName = '';
    private ?string $className = null;

    /**
     * @throws Exception
     */
    public static function delete(int $id): void {
        StandaloneEventEntriesRepository::delete($id);
    }

    public static function fromStdClass(?stdClass $data): ?self {
        if (!$data) {
            return null;
        }

        $result = new self();
        $result->setId((int)$data->id);
        $result->setStandaloneEventId((int)$data->standaloneEventId);
        $result->setEventClassId($data->eventClassId !== null ? (int)$data->eventClassId : null);
        $result->setCarId((int)$data->carId);
        $result->setUserId((int)$data->userId);
        $result->setMemberName($data->memberName ?? '');
        $result->setFirstName($data->firstName ?? '');
        $result->setLastName($data->lastName ?? '');
        $result->setRaceNumber((int)($data->raceNumber ?? 0));
        $result->setAvatarUrl(get_avatar_url((int)$data->userId) ?: '');
        $result->setCarName($data->carName ?? '');
        $result->setClassName($data->className ?? null);

        return $result;
    }

    /**
     * @throws Exception
     */
    public static function get(int $id): ?self {
        $row = StandaloneEventEntriesRepository::getById($id);

        return self::fromStdClass($row);
    }

    /**
     * @return StandaloneEventEntry[]
     * @throws Exception
     */
    public static function listByStandaloneEvent(int $standaloneEventId): array {
        $results = StandaloneEventEntriesRepository::listByStandaloneEventId($standaloneEventId);

        return array_map(fn($row) => self::fromStdClass($row), $results);
    }

    public function getStandaloneEventId(): int {
        return $this->standaloneEventId;
    }

    public function setStandaloneEventId(int $value): void {
        $this->standaloneEventId = $value;
    }

    public function getEventClassId(): ?int {
        return $this->eventClassId;
    }

    public function setEventClassId(?int $value): void {
        $this->eventClassId = $value;
    }

    public function getCarId(): int {
        return $this->carId;
    }

    public function setCarId(int $value): void {
        $this->carId = $value;
    }

    public function getUserId(): int {
        return $this->userId;
    }

    public function setUserId(int $value): void {
        $this->userId = $value;
    }

    public function getMemberName(): string {
        return $this->memberName;
    }

    private function setMemberName(string $value): void {
        $this->memberName = $value;
    }

    public function getFirstName(): string {
        return $this->firstName;
    }

    private function setFirstName(string $value): void {
        $this->firstName = $value;
    }

    public function getLastName(): string {
        return $this->lastName;
    }

    private function setLastName(string $value): void {
        $this->lastName = $value;
    }

    public function getRaceNumber(): int {
        return $this->raceNumber;
    }

    private function setRaceNumber(int $value): void {
        $this->raceNumber = $value;
    }

    public function getAvatarUrl(): string {
        return $this->avatarUrl;
    }

    private function setAvatarUrl(string $value): void {
        $this->avatarUrl = $value;
    }

    public function getCarName(): string {
        return $this->carName;
    }

    private function setCarName(string $value): void {
        $this->carName = $value;
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
            $this->setId(StandaloneEventEntriesRepository::add($this->toArray()));
        }

        return $this;
    }

    public function toArray(): array {
        $data = [
            'standaloneEventId' => $this->getStandaloneEventId(),
            'carId'             => $this->getCarId(),
            'userId'            => $this->getUserId(),
        ];

        if ($this->getEventClassId() !== null) {
            $data['eventClassId'] = $this->getEventClassId();
        }

        return $data;
    }

    public function toDto(): array {
        return [
            'id'                => $this->getId(),
            'standaloneEventId' => $this->getStandaloneEventId(),
            'eventClassId'      => $this->getEventClassId(),
            'carId'             => $this->getCarId(),
            'userId'            => $this->getUserId(),
            'memberName'        => $this->getMemberName(),
            'firstName'         => $this->getFirstName(),
            'lastName'          => $this->getLastName(),
            'raceNumber'        => $this->getRaceNumber(),
            'avatarUrl'         => $this->getAvatarUrl(),
            'carName'           => $this->getCarName(),
            'className'         => $this->getClassName(),
        ];
    }
}
