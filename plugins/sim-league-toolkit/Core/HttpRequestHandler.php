<?php

  namespace SLTK\Core;

  abstract class HttpRequestHandler {

    protected function getActionFromPost(): string {
      return $this->getFieldFromPost(FieldNames::PAGE_ACTION, Constants::ACTION_EDIT);
    }

    protected function getActionFromUrl(): string {
      return $this->getFieldFromUrl(QueryParamNames::ACTION, Constants::ACTION_EDIT);
    }

    protected function getFieldFromPost(string $fieldId, string $defaultValue = null): string|null {
      return $_POST[$fieldId] ?? $defaultValue;
    }

    protected function getFieldFromUrl(string $fieldId, string $defaultValue = null): string|null {
      return $_GET[$fieldId] ?? $defaultValue;
    }

    protected function getFile(string $fieldId): array|null {
      return $_FILES[$fieldId] ?? null;
    }

    protected function getIdFromUrl(): int {
      return $this->getSanitisedFieldFromUrl(QueryParamNames::ID, Constants::DEFAULT_ID);
    }

    protected function getReturnFromUrl(): string {
      return $this->getSanitisedFieldFromUrl(QueryParamNames::RETURN, 'notifications');
    }

    protected function getSanitisedFieldFromPost(string $fieldId, string $defaultValue = null): string|null {
      return sanitize_text_field($this->getFieldFromPost($fieldId, $defaultValue));
    }

    protected function getSanitisedFieldFromUrl(string $fieldId, string $defaultValue = null): string|null {
      return sanitize_text_field($this->getFieldFromUrl($fieldId, $defaultValue));
    }

    protected function getTabFromUrl(): string {
      return $this->getSanitisedFieldFromUrl(QueryParamNames::TAB);
    }

    protected function isFormPost(): bool {
      return strtoupper($_SERVER[FieldNames::REQUEST_METHOD]) === 'POST';
    }

    protected function isGetRequest(): bool {
      return strtoupper($_SERVER[FieldNames::REQUEST_METHOD]) === 'GET';
    }

    protected function postContainsField(string $fieldId): bool {
      return !empty($_POST[$fieldId]);
    }
  }