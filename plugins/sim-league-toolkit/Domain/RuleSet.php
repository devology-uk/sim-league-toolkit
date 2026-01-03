<?php

  namespace SLTK\Domain;

  use Exception;
  use SLTK\Core\Constants;
  use SLTK\Database\Repositories\RuleSetRepository;
  use stdClass;

  class RuleSet extends DomainBase {
    private string $description = '';
    private string $name = '';
    private string $type = '';

    public function __construct(stdClass $data = null) {
      if ($data != null) {
        $this->id = $data->id;
        $this->name = $data->name ?? '';
        $this->description = $data->description ?? '';
        $this->type = $data->type ?? '';
      }
    }

    /**
     * @throws Exception
     */
    public static function delete(int $id): void {
      RuleSetRepository::delete($id);
    }

    public static function deleteRule(int $id): void {
      RuleSetRepository::deleteRule($id);
    }

    public static function get(int $id): RuleSet|null {
      $result = RuleSetRepository::getById($id);

      if ($result != null) {
        return new RuleSet($result);
      }

      return null;
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
      $results = RuleSetRepository::list();

      return self::mapRuleSets($results);
    }

    /**
     * @return RuleSetRule[]
     * @throws Exception
     */
    public static function listRules(mixed $id): array {
      $ruleSet = RuleSet::get($id);

      return $ruleSet->getRules() ?? [];
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

    /**
     * @return RuleSetRule[]
     * @throws Exception
     */
    public function getRules(): array {
      return self::mapRuleSetRules(RuleSetRepository::listRules($this->id));
    }

    public function getType(): string {
      return $this->type;
    }

    public function setType(string $type): void {
      $this->type = $type;
    }

    public function save(): bool {
      try {
        if ($this->id == Constants::DEFAULT_ID) {
          $this->id = RuleSetRepository::add($this->toArray(false));
        } else {
          RuleSetRepository::update($this->id, $this->toArray(false));
        }

        return true;
      } catch (Exception) {
        return false;
      }
    }

    public function saveRule(RuleSetRule $rule): bool {
      try {
        $existing = RuleSetRepository::getRuleById($rule->id);
        if (isset($existing->id)) {
          RuleSetRepository::updateRule($existing->id, $rule->toArray(false));
        } else {
          $rule->id = RuleSetRepository::addRule($rule->toArray(false));
        }

        return true;
      } catch (Exception) {
        return false;
      }
    }

    /**
     * @return array{columnName: string, value: mixed}
     */
    public function toArray(bool $includeId = true): array {
      $result = [
        'name' => $this->name,
        'description' => $this->description,
        'type' => $this->type,
      ];

      if ($includeId && $this->id != Constants::DEFAULT_ID) {
        $result['id'] = $this->id;
      }

      return $result;
    }

    /**
     * @return array{columnName: string, value: mixed}
     */
    public function toDto(): array {
      return [
        'id' => $this->id,
        'name' => $this->name,
        'description' => $this->description,
        'type' => $this->type,
      ];
    }

    private static function mapRuleSetRules(array $queryResults): array {
      $results = array();

      foreach ($queryResults as $item) {
        $results[] = new RuleSetRule($item);
      }

      return $results;
    }

    private static function mapRuleSets(array $queryResults): array {
      $results = array();

      foreach ($queryResults as $item) {
        $results[] = new RuleSet($item);
      }

      return $results;
    }
  }