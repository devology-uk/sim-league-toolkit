<?php

  namespace SLTK\Domain;

  use SLTK\Domain\Abstractions\ProvidesPersistableArray;
  use SLTK\Domain\Abstractions\ValueObject;
  use SLTK\Domain\Traits\HasIdentity;
  use stdClass;

  class RuleSetRule implements ValueObject, ProvidesPersistableArray {
    use HasIdentity;

    private string $rule = '';
    private int $ruleSetId = 0;

    public static function fromStdClass(?stdClass $data): ?self {
      if (!$data) {
        return null;
      }

      $result = new self();

      $result->setId($data->id);
      $result->setRule($data->rule);
      $result->setRuleSetId($data->ruleSetId);

      return $result;
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
    public function toArray(): array {
      return [
        'ruleSetId' => $this->getRuleSetId(),
        'rule' => $this->getRule(),
      ];
    }

    /**
     * @return array{columnName: string, value: mixed}
     */
    public function toDto(): array {
      return [
        'id' => $this->getId(),
        'ruleSetId' => $this->getRuleSetId(),
        'rule' => $this->getRule(),
      ];
    }
  }