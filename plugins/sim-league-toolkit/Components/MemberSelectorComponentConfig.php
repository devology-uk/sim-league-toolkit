<?php

  namespace SLTK\Components;

  /**
   * Configuration settings for the Member Selector Component
   */
  class MemberSelectorComponentConfig {
    /**
     * @var bool Indicates whether the component should be disabled once an existing value has been set
     */
    public bool $disableOnSetValue = false;
    /**
     * @var bool Indicates whether the component should trigger a post request when a value is selected
     */
    public bool $submitOnSelect = false;
  }