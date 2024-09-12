<?php

  namespace SLTK\Components;

  /**
   * Configuration settings for the Platform Selector Component
   */
  class PlatformSelectorComponentConfig {
    /**
     * @var bool Indicates whether the component should be disabled once an existing value has been set
     */
    public bool $disableOnSetValue = false;
    /**
     * @var bool Indicates whether the component should trigger a post request when a value is selected
     */
    public bool $submitOnSelect = false;
  }