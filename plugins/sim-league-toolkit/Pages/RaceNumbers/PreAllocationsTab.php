<?php

  namespace SLTK\Pages\RaceNumbers;

  use SLTK\Pages\AdminTab;

  /**
   * Presents pre allocated race numbers in a tab
   */
  class PreAllocationsTab implements AdminTab {

    public final const string NAME = 'pre-allocations';

    private PreAllocationsTabController $controller;

    public function __construct() {
      $this->controller = new PreAllocationsTabController();
    }

    /**
     * @inheritDoc
     */
    public function render(): void {
      // TODO: Implement render() method.
    }
  }