<?php

  namespace SLTK\Components;

  use SLTK\Core\CommonFieldNames;

  abstract class FormFieldComponent implements Component {

    public abstract function getTooltip(): string;

    public abstract function getValue(): mixed;

    public abstract function setValue(mixed $value): void;

    protected function isFormPost(): bool {
      return strtoupper($_SERVER[CommonFieldNames::REQUEST_METHOD]) === 'POST';
    }

    protected function getPostedValue(string $fieldName): string {
      return sanitize_text_field($_POST[$fieldName]);
    }
  }