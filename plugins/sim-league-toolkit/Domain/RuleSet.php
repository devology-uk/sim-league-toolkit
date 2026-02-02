<?php

  namespace SLTK\Domain;

  use Exception;
  use SLTK\Core\Constants;
  use SLTK\Database\Repositories\RuleSetRepository;
  use SLTK\Domain\Abstractions\AggregateRoot;
  use SLTK\Domain\Abstractions\Deletable;
  use SLTK\Domain\Abstractions\Listable;
  use SLTK\Domain\Abstractions\ProvidesPersistableArray;
  use SLTK\Domain\Abstractions\Saveable;
  use SLTK\Domain\Traits\HasIdentity;
  use stdClass;

  class RuleSet implements AggregateRoot, Deletable, Listable, ProvidesPersistableArray, Saveable {
    use HasIdentity;

    private string $description = '';
    private string $name = '';
    private string $type = '';

    /**
     * @throws Exception
     */
    public static function delete(int $id): void {
      RuleSetRepository::delete($id);
    }

    /**
     * @throws Exception
     */
    public static function deleteRule(int $id): void {
      RuleSetRepository::deleteRule($id);
    }

    public static function fromStdClass(?stdClass $data): ?self {
      if (!$data) {
        return null;
      }

      $result = new self();

      $result->setName($data->name ?? '');
      $result->setDescription($data->description ?? '');
      $result->setType($data->type ?? '');

      return $result;
    }

    public static function get(int $id): RuleSet|null {
      $result = RuleSetRepository::getById($id);

      return self::fromStdClass($result);
    }

    /**
     * @throws Exception
     */
    public static function getRuleById(int $ruleSetRuleId): RuleSetRule|null {
      $result = RuleSetRepository::getRuleById($ruleSetRuleId);

      if ($result != null) {
        return new RuleSetRule($result);
      }

      return null;
    }

    /**
     * @return RuleSet[]
     */
    public static function list(): array {
      $queryResults = RuleSetRepository::list();

      return array_map(function ($item) {
        return self::fromStdClass($item);
      }, $queryResults);
    }

    /**
     * @return RuleSetRule[]
     * @throws Exception
     */
    public static function listRules(int $ruleSetId): array {
      $queryResults = RuleSetRepository::listRules($ruleSetId);

      return array_map(function ($item) {
        return RuleSetRule::fromStdClass($item);
      }, $queryResults);
    }

    public function getDescription(): string {
      return $this->description;
    }

    public function setDescription(string $description): void {
      $this->description = $description;
    }

    public function getName(): string {
      return $this->name;
    }

    public function setName(string $name): void {
      $this->name = $name;
    }

    public function getType(): string {
      return $this->type;
    }

    public function setType(string $type): void {
      $this->type = $type;
    }

    /**
     * @throws Exception
     */
    public function save(): self {

      if ($this->getId() == Constants::DEFAULT_ID) {
        $this->setId(RuleSetRepository::add($this->toArray()));
      } else {
        RuleSetRepository::update($this->getId(), $this->toArray());
      }

      return $this;
    }

    public function saveRule(RuleSetRule $rule): bool {
      try {
        $existing = RuleSetRepository::getRuleById($rule->getId());
        if (isset($existing->id)) {
          RuleSetRepository::updateRule($existing->id, $rule->toArray(false));
        } else {
          $rule->setId(RuleSetRepository::addRule($rule->toArray(false)));
        }

        return true;
      } catch (Exception) {
        return false;
      }
    }

    /**
     * @return array{columnName: string, value: mixed}
     */
    public function toArray(): array {
      return [
        'name' => $this->getName(),
        'description' => $this->getDescription(),
        'type' => $this->getType(),
      ];
    }

    /**
     * @return array{columnName: string, value: mixed}
     */
    public function toDto(): array {
      return [
        'id' => $this->getId(),
        'name' => $this->getName(),
        'description' => $this->getDescription(),
        'type' => $this->getType(),
      ];
    }
  }