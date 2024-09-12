<?php

  namespace SLTK\Core;

  /**
   * Provides methods for accessing HTTP request state
   */
  abstract class HttpRequestHandler {
    /**
     * @return string The value of the action field from the current post
     */
    protected function getActionFromPost(): string {
      return $this->getFieldFromPost(FieldNames::PAGE_ACTION, Constants::ACTION_EDIT);
    }

    /**
     * @return string The value of the action query parameter from the current url
     */
    protected function getActionFromUrl(): string {
      return $this->getFieldFromUrl(QueryParamNames::ACTION, Constants::ACTION_EDIT);
    }

    /**
     * @param string $fieldId The id of the target field in the post request
     * @param string|null $defaultValue The value to return if the field does not exist
     *
     * @return string|null The value of the specified field or the specified default
     */
    protected function getFieldFromPost(string $fieldId, string $defaultValue = null): string|null {
      return $_POST[$fieldId] ?? $defaultValue;
    }

    /**
     * @param string $fieldId The id of the target parameter in the url query
     * @param string|null $defaultValue The value to return if the parameter does not exist
     *
     * @return string|null The value of the specified parameter or the specified default
     */
    protected function getFieldFromUrl(string $fieldId, string $defaultValue = null): string|null {
      return $_GET[$fieldId] ?? $defaultValue;
    }

    /**
     * @param string $fieldId The if of the target posted file
     *
     * @return array|null The posted file details if it exists
     */
    protected function getFile(string $fieldId): array|null {
      return $_FILES[$fieldId] ?? null;
    }

    /**
     * @return int The value of the id parameter from the query of the current url
     */
    protected function getIdFromUrl(): int {
      return $this->getSanitisedFieldFromUrl(QueryParamNames::ID, Constants::DEFAULT_ID);
    }

    /**
     * @return string The value of the return parameter from the query of the current url
     */
    protected function getReturnFromUrl(): string {
      return $this->getSanitisedFieldFromUrl(QueryParamNames::RETURN, 'notifications');
    }

    /**
     * @param string $fieldId The id of the target field in the post request
     * @param string|null $defaultValue The value to return if the field does not exist
     *
     * @return string|null Sanitized value of the specified field or the default value
     */
    protected function getSanitisedFieldFromPost(string $fieldId, string $defaultValue = null): string|null {
      return sanitize_text_field($this->getFieldFromPost($fieldId, $defaultValue));
    }

    /**
     * @param string $fieldId The id of the target parameter in the query of the current url
     * @param string|null $defaultValue The value to return if the parameter does not exist
     *
     * @return string|null Sanitized value of the specified parameter or the default value
     */
    protected function getSanitisedFieldFromUrl(string $fieldId, string $defaultValue = null): string|null {
      return sanitize_text_field($this->getFieldFromUrl($fieldId, $defaultValue));
    }

    /**
     * @return string The value of the tab query parameter from the current url
     */
    protected function getTabFromUrl(): string {
      return $this->getSanitisedFieldFromUrl(QueryParamNames::TAB);
    }

    protected function isFormPost(): bool {
      return strtoupper($_SERVER[FieldNames::REQUEST_METHOD]) === 'POST';
    }

    /**
     * @return bool Indicates whether the current request is a get request
     */
    protected function isGetRequest(): bool {
      return strtoupper($_SERVER[FieldNames::REQUEST_METHOD]) === 'GET';
    }

    /**
     * @param string $fieldId The id of the target field
     *
     * @return bool Indicates whether the current post request contains the specified field
     */
    protected function postContainsField(string $fieldId): bool {
      return !empty($_POST[$fieldId]);
    }
  }