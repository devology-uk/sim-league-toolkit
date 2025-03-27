<?php

  namespace SLTK\Domain;

  use Exception;
  use SLTK\Core\Constants;
  use SLTK\Database\Repositories\RuleSetRepository;
  use stdClass;

  class RuleSet extends DomainBase implements TableItem, Validator {
    public final const string NAME_FIELD_NAME = 'sltk_name';
    public final const string TYPE_CHAMPIONSHIP = 'Championship';
    public final const string TYPE_EVENT = 'Event';
    public final const string TYPE_FIELD_NAME = 'sltk_type';
    private string $name = '';
    private string $type = '';

    public function __construct(stdClass $data = null) {
      if ($data != null) {
        $this->id = $data->id;
        $this->name = $data->name ?? '';
        $this->type = $data->type ?? '';
      }
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
     * @return RuleSet[] Collection of all rule sets
     */
    public static function list(): array {
      $results = RuleSetRepository::list();

      return self::mapRuleSets($results);
    }

    public function deleteRule(int $ruleId): bool {
      try {
        RuleSetRepository::deleteRule($ruleId);

        return true;
      } catch (Exception) {
        return false;
      }
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
    public function toTableItem(): array {
      return [
        'id' => $this->id,
        'name' => $this->name,
        'type' => $this->type,
      ];
    }

    public function validate(): ValidationResult {
      $result = new ValidationResult();

      if (empty($this->name)) {
        $result->addValidationError(self::NAME_FIELD_NAME, esc_html__('Name is required.', 'sim-league-toolkit'));
      }

      if (empty($this->type)) {
        $result->addValidationError(self::TYPE_FIELD_NAME,
          esc_html__('Type is required.', 'sim-league-toolkit'));
      }

      return $result;
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