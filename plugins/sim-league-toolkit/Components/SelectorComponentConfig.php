<?php

  namespace SLTK\Components;

  class SelectorComponentConfig {
    public function __construct(bool $disableOnSetValue = false, bool $submitOnSelect = false, string $toolTip = '') {
      $this->disableOnSetValue = $disableOnSetValue;
      $this->submitOnSelect = $submitOnSelect;
      $this->toolTip = $toolTip;
    }

    public bool $disableOnSetValue = false;
    public bool $submitOnSelect = false;
    public string $toolTip = '';
  }