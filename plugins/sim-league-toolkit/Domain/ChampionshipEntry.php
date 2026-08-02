<?php

namespace SLTK\Domain;

use Exception;
use SLTK\Core\Constants;
use SLTK\Database\Repositories\ChampionshipEntriesRepository;
use SLTK\Domain\Abstractions\AggregateRoot;
use SLTK\Domain\Abstractions\Deletable;
use SLTK\Domain\Abstractions\ProvidesPersistableArray;
use SLTK\Domain\Abstractions\Saveable;
use SLTK\Domain\Traits\HasEntrantFields;
use SLTK\Domain\Traits\HasIdentity;
use stdClass;

class ChampionshipEntry implements AggregateRoot, Deletable, ProvidesPersistableArray, Saveable {
    use HasEntrantFields, HasIdentity;

    private int $championshipId = Constants::DEFAULT_ID;
    private string $className = '';
    private int $eventClassId = Constants::DEFAULT_ID;

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
        $result->setClassName($data->className ?? '');
        $result->hydrateEntrantFieldsFromStdClass($data);

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

    public function getClassName(): string {
        return $this->className;
    }

    private function setClassName(string $value): void {
        $this->className = $value;
    }

    public function getEventClassId(): int {
        return $this->eventClassId;
    }

    public function setEventClassId(int $value): void {
        $this->eventClassId = $value;
    }

    /**
     * @throws Exception
     */
    public function save(): self {
        if (!$this->hasId()) {
            $this->setId(ChampionshipEntriesRepository::add($this->toArray()));
        } else {
            ChampionshipEntriesRepository::update($this->getId(), $this->toArray());
        }

        return $this;
    }

    public function toArray(): array {
        return array_merge($this->entrantFieldsToArray(), [
            'championshipId' => $this->getChampionshipId(),
            'eventClassId' => $this->getEventClassId(),
        ]);
    }

    public function toDto(): array {
        return array_merge(['id' => $this->getId()], $this->entrantFieldsToDto(), [
            'championshipId' => $this->getChampionshipId(),
            'eventClassId' => $this->getEventClassId(),
            'className' => $this->getClassName(),
        ]);
    }
}
