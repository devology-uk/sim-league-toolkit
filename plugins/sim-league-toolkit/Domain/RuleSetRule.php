<?php

  namespace SLTK\Domain;

  use Exception;
  use SLTK\Core\Constants;
  use SLTK\Database\Repositories\RuleSetRepository;
  use stdClass;

  class RuleSetRule extends EntityBase implements TableItem, Validator {
    public final const string RULE_FIELD_NAME = 'sltk_rule';
    public final const string RULE_INDEX_FIELD_NAME = 'sltk_rule_index';
    public final const string RULE_SET_ID_FIELD_NAME = 'sltk_rule_set_id';

    private string $rule = '';
    private int $ruleSetId = 0;

    public function __construct(?stdClass $data = null) {
      if ($data) {
        $this->id = $data->id;
        $this->rule = $data->rule;
        $this->ruleSetId = $data->ruleSetId;
      }
    }

    /**
     * @throws Exception
     */
    public static function delete(int $id): void {
      RuleSetRepository::deleteRule($id);
    }

    public function getRule(): string {
      return $this->rule;
    }

    public function setRule(string $rule): void {
      $this->rule = $rule;
    }

    public function getRuleSetId(): int {
      return $this->ruleSetId;
    }

    public function setRuleSetId(int $ruleSetId): void {
      $this->ruleSetId = $ruleSetId;
    }

    /**
     * @throws Exception
     */
    public function save(): bool {
      try {
        if ($this->id == Constants::DEFAULT_ID) {
          $this->id = RuleSetRepository::addRule($this->toArray(false));
        } else {
          RuleSetRepository::updateRule($this->id, $this->toArray(false));
        }

        return true;
      } catch (Exception) {
        return false;
      }
    }

    /**
     * @return array{fieldName: string, value: mixed}
     */
    public function toArray(bool $includeId = true): array {
      $result = [
        'ruleSetId' => $this->ruleSetId,
        'rule' => $this->rule,
      ];

      if ($includeId && $this->id !== Constants::DEFAULT_ID) {
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
        'ruleSetId' => $this->ruleSetId,
        'rule' => $this->rule,
      ];
    }

    /**
     * @return array{columnName: string, value: mixed}
     */
    public function toTableItem(): array {
      return [
        'id' => $this->id,
        'ruleSetId' => $this->ruleSetId,
        'rule' => $this->rule,
      ];
    }

    public function validate(): ValidationResult {
      $result = new ValidationResult();

      if ($this->ruleSetId < 1) {
        $result->addValidationError(self::RULE_SET_ID_FIELD_NAME,
          esc_html__('The parent Rule Set id is required', 'sim-league-toolkit'));
      }

      if (empty($this->rule) || strlen($this->rule) < 20) {
        $result->addValidationError(self::RULE_FIELD_NAME,
          esc_html__('Rule is required and must be at least 20 characters', 'sim-league-toolkit'));
      }

      return $result;
    }
  }