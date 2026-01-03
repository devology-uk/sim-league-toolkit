<?php

  namespace SLTK\Domain;

  use Exception;
  use SLTK\Core\Constants;
  use SLTK\Database\Repositories\RuleSetRepository;
  use stdClass;

  class RuleSetRule extends EntityBase {
    private string $rule = '';
    private int $ruleSetId = 0;

    public function __construct(?stdClass $data = null) {
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
    public function toDto(): array {
      return [
        'id' => $this->id,
        'ruleSetId' => $this->ruleSetId,
        'rule' => $this->rule,
      ];
    }
  }