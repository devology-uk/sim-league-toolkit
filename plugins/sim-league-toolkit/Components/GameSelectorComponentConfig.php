<?php

  namespace SLTK\Components;

  /**
   * Configuration settings for the Game Selector Component
   */
  class GameSelectorComponentConfig {
    /**
     * @var bool Indicates whether the component should be disabled once an existing value has been set
     */
    public bool $disableOnSetValue = true;
    /**
     * @var bool Indicates whether the component should trigger a post request when a value is selected
     */
    public bool $submitOnSelect = true;
  }