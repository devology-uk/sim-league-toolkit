<?php

  namespace SLTK\Domain;

  class ValidationResult {
    public bool $success = true;
    /**
     * @var string[]
     */
    public array $validationErrors = [];

    public function addValidationError(string $field, string $error): void {
      $this->success = false;
      $this->validationErrors[$field] = $error;
    }
  }