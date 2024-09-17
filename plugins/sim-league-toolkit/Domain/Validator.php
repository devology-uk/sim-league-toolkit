<?php

  namespace SLTK\Domain;

  interface Validator {
    public function validate(): ValidationResult;
  }