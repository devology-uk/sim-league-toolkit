<?php

  namespace SLTK\Components;

  /**
   * Defines the members that must be implemented by a Component
   */
  interface Component {
    /**
     * Renders the output of the component
     *
     * @return void
     */
    public function render(): void;
  }