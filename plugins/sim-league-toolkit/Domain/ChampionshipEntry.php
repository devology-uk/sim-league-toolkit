<?php

namespace SLTK\Domain;

use Exception;
use SLTK\Core\Constants;
use SLTK\Database\Repositories\ChampionshipEntriesRepository;
use SLTK\Domain\Abstractions\AggregateRoot;
use SLTK\Domain\Abstractions\Deletable;
use SLTK\Domain\Abstractions\ProvidesPersistableArray;
use SLTK\Domain\Abstractions\Saveable;
use SLTK\Domain\Traits\HasIdentity;
use stdClass;

class ChampionshipEntry implements AggregateRoot, Deletable, ProvidesPersistableArray, Saveable {
    use HasIdentity;

    private int $championshipId = Constants::DEFAULT_ID;
    private int $eventClassId = Constants::DEFAULT_ID;
    private int $carId = Constants::DEFAULT_ID;
    private int $userId = Constants::DEFAULT_ID;
    private string $memberName = '';
    private string $className = '';
    private string $carName = '';

    /**
     * @throws Exception
     */
    public static function delete(int $id): void {
        ChampionshipEntriesRepository::delete($id);
    }

    public static function fromStdClass(?stdClass $data): ?self {
        if (!$data) {
            return null;
        }

        $result = new self();
        $result->setId((int)$data->id);
        $result->setChampionshipId((int)$data->championshipId);
        $result->setEventClassId((int)$data->eventClassId);
        $result->setCarId((int)$data->carId);
        $result->setUserId((int)$data->userId);
        $result->setMemberName($data->memberName ?? '');
        $result->setClassName($data->className ?? '');
        $result->setCarName($data->carName ?? '');

        return $result;
    }

    /**
     * @throws Exception
     */
    public static function get(int $id): ?self {
        $row = ChampionshipEntriesRepository::getById($id);

        return self::fromStdClass($row);
    }

    /**
     * @return ChampionshipEntry[]
     * @throws Exception
     */
    public static function listByChampionship(int $championshipId): array {
        $results = ChampionshipEntriesRepository::listByChampionshipId($championshipId);

        return array_map(fn($row) => self::fromStdClass($row), $results);
    }

    public function getChampionshipId(): int {
        return $this->championshipId;
    }

    public function setChampionshipId(int $value): void {
        $this->championshipId = $value;
    }

    public function getEventClassId(): int {
        return $this->eventClassId;
    }

    public function setEventClassId(int $value): void {
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

    public function getClassName(): string {
        return $this->className;
    }

    private function setClassName(string $value): void {
        $this->className = $value;
    }

    public function getCarName(): string {
        return $this->carName;
    }

    private function setCarName(string $value): void {
        $this->carName = $value;
    }

    /**
     * @throws Exception
     */
    public function save(): self {
        if (!$this->hasId()) {
            $this->setId(ChampionshipEntriesRepository::add($this->toArray()));
        }

        return $this;
    }

    public function toArray(): array {
        return [
            'championshipId' => $this->getChampionshipId(),
            'eventClassId'   => $this->getEventClassId(),
            'carId'          => $this->getCarId(),
            'userId'         => $this->getUserId(),
        ];
    }

    public function toDto(): array {
        return [
            'id'             => $this->getId(),
            'championshipId' => $this->getChampionshipId(),
            'eventClassId'   => $this->getEventClassId(),
            'carId'          => $this->getCarId(),
            'userId'         => $this->getUserId(),
            'memberName'     => $this->getMemberName(),
            'className'      => $this->getClassName(),
            'carName'        => $this->getCarName(),
        ];
    }
}
