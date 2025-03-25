<?php

  namespace SLTK\Domain;

  use SLTK\Core\Constants;
  use stdClass;

  class RuleSetRule extends EntityBase implements TableItem, Validator {
    public final const string RULE_SET_ID_FIELD_NAME = 'sltk_rule_set_id';
    public final const string RULE_FIELD_NAME = 'sltk_rule';

    private string $rule = '';
    private int $ruleSetId = 0;

    public function __construct(stdClass $data = null) {
      if ($data) {
        $this->id = $data->id;
        $this->rule = $data->rule;
        $this->ruleSetId = $data->ruleSetId;
      }
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
    public function toTableItem(): array {
      return [
        'id' => $this->id,
        'ruleSetId' => $this->ruleSetId,
        'rule' => $this->rule,
      ];
    }

    public function validate(): ValidationResult {
      $result = new ValidationResult();

      if($this->ruleSetId < 1) {
        $result->addValidationError(self::RULE_SET_ID_FIELD_NAME,
          esc_html__('The parent Rule Set id is required', 'sim-league-toolkit'));
      }

      if(empty($this->rule) || strlen($this->rule) < 20) {
        $result->addValidationError(self::RULE_FIELD_NAME,
          esc_html__('Rule is required and must be at least 20 characters', 'sim-league-toolkit'));
      }

      return $result;
    }
  }