<?php

  namespace SLTK\Pages\RaceNumbers;

  use SLTK\Core\AdminPageSlugs;
  use SLTK\Pages\ControllerBase;

  /**
   * Renders a page for managing member race numbers
   */
  class RaceNumbersAdminPageController extends ControllerBase {
    private string $currentTab = '';

    /**
     * Renders the active race number allocations tab
     * @return void
     */
    public function theActiveAllocationsTab(): void { ?>
      <a href='?page=<?= AdminPageSlugs::RACE_NUMBERS ?>'
         class="nav-tab <?= empty($this->currentTab) ? 'nav-tab-active' : '' ?>"><?= esc_html__('Allocations', 'sim-league-tool-kit') ?></a>
      <?php
    }

    /**
     * Renders the pre-allocated race numbers tab
     *
     * @return void
     */
    public function thePreAllocationsTab(): void {
      $tabName = PreAllocationsTab::NAME;
      ?>
      <a href='?page=<?= AdminPageSlugs::RACE_NUMBERS ?>&tab=<?= $tabName ?>'
         class="nav-tab <?= $this->currentTab === $tabName ? 'nav-tab-active' : '' ?>"><?= esc_html__('Pre-Allocations', 'sim-league-tool-kit') ?></a>
      <?php
    }

    /**
     * Renders the content for the active tab
     *
     * @return void
     */
    public function theTabContent(): void {
      $tab = match ($this->currentTab) {
        PreAllocationsTab::NAME => new PreAllocationsTab(),
        default => new ActiveAllocationsTab(),
      };
      $tab->render();
    }

    /**
     * @inheritDoc
     */
    protected function handleGet(): void {
      $this->currentTab = $this->getTabFromUrl();
    }

    /**
     * @inheritDoc
     */
    protected function handlePost(): void {
      $this->currentTab = $this->getTabFromUrl();
    }
  }