<?php

namespace SLTK\Domain;

use Exception;
use SLTK\Core\Constants;
use SLTK\Database\Repositories\StandaloneEventEntriesRepository;
use SLTK\Domain\Abstractions\AggregateRoot;
use SLTK\Domain\Abstractions\Deletable;
use SLTK\Domain\Abstractions\ProvidesPersistableArray;
use SLTK\Domain\Abstractions\Saveable;
use SLTK\Domain\Traits\HasEntrantFields;
use SLTK\Domain\Traits\HasIdentity;
use stdClass;

class StandaloneEventEntry implements AggregateRoot, Deletable, ProvidesPersistableArray, Saveable {
    use HasEntrantFields, HasIdentity;

    private ?string $className = null;
    private ?int $eventClassId = null;
    private int $standaloneEventId = Constants::DEFAULT_ID;

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
        $result->setClassName($data->className ?? null);
        $result->hydrateEntrantFieldsFromStdClass($data);

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

    /**
     * @return StandaloneEventEntry[]
     * @throws Exception
     */
    public static function listByUserId(int $userId): array {
        $results = StandaloneEventEntriesRepository::listByUserId($userId);

        return array_map(fn($row) => self::fromStdClass($row), $results);
    }

    public function getClassName(): ?string {
        return $this->className;
    }

    private function setClassName(?string $value): void {
        $this->className = $value;
    }

    public function getEventClassId(): ?int {
        return $this->eventClassId;
    }

    public function setEventClassId(?int $value): void {
        $this->eventClassId = $value;
    }

    public function getStandaloneEventId(): int {
        return $this->standaloneEventId;
    }

    public function setStandaloneEventId(int $value): void {
        $this->standaloneEventId = $value;
    }

    /**
     * @throws Exception
     */
    public function save(): self {
        if (!$this->hasId()) {
            $this->setId(StandaloneEventEntriesRepository::add($this->toArray()));
        } else {
            StandaloneEventEntriesRepository::update($this->getId(), $this->toArray());
        }

        return $this;
    }

    public function toArray(): array {
        $data = array_merge($this->entrantFieldsToArray(), [
            'standaloneEventId' => $this->getStandaloneEventId(),
        ]);

        if ($this->getEventClassId() !== null) {
            $data['eventClassId'] = $this->getEventClassId();
        }

        return $data;
    }

    public function toDto(): array {
        return array_merge(['id' => $this->getId()], $this->entrantFieldsToDto(), [
            'standaloneEventId' => $this->getStandaloneEventId(),
            'eventClassId' => $this->getEventClassId(),
            'className' => $this->getClassName(),
        ]);
    }
}
