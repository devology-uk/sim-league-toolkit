<?php

  namespace SLTK\Pages\Game;

  use SLTK\Core\AdminPageSlugs;
  use SLTK\Core\Constants;
  use SLTK\Core\QueryParamNames;
  use SLTK\Core\UrlBuilder;
  use SLTK\Domain\Game;
  use SLTK\Pages\ControllerBase;
  use SLTK\Pages\Game\Tabs\GameCarClassesTab;
  use SLTK\Pages\Game\Tabs\GameCarsTab;
  use SLTK\Pages\Game\Tabs\GameDetailsTab;
  use SLTK\Pages\Game\Tabs\GameDriverCategoriesTab;
  use SLTK\Pages\Game\Tabs\GameTracksTab;

  class GameAdminPageController extends ControllerBase {

    private string $currentTab;
    private Game $game;
    private int $id = Constants::DEFAULT_ID;
    private GameDetailsTab|GameCarClassesTab|GameDriverCategoriesTab|GameCarsTab|GameTracksTab $tab;

    public function theBackButton(): void { ?>
        <br/>
        <p>
            <a href="<?= get_admin_url() . 'admin.php?page=' . AdminPageSlugs::GAMES ?>"
               class='button button-secondary'><?= esc_html__('Back to Games', 'sim-league-toolkit') ?></a>
        </p>
      <?php
    }

    public function theCarsTab(): void {
      if ($this->id === Constants::DEFAULT_ID) {
        return;
      }

      ?>
        <a href="<?= $this->getTabUrl(Game::CARS_TAB) ?>"
           class="nav-tab <?= $this->getActiveCssClass(Game::CARS_TAB) ?>"><?= esc_html__('Cars', 'sim-league-toolkit') ?></a>
      <?php
    }

      public function theTracksTab(): void {
          if ($this->id === Constants::DEFAULT_ID) {
              return;
          }

          ?>
          <a href="<?= $this->getTabUrl(Game::TRACKS_TAB) ?>"
             class="nav-tab <?= $this->getActiveCssClass(Game::TRACKS_TAB) ?>"><?= esc_html__('Tracks', 'sim-league-toolkit') ?></a>
          <?php
      }

    public function theCarClassesTab(): void {
      if ($this->id === Constants::DEFAULT_ID) {
        return;
      }

      ?>
        <a href="<?= $this->getTabUrl(Game::CAR_CLASSES_TAB) ?>"
           class="nav-tab <?= $this->getActiveCssClass(Game::CAR_CLASSES_TAB) ?>"><?= esc_html__('Car Classes', 'sim-league-toolkit') ?></a>
      <?php
    }

    public function theDriverCategoriesTab(): void {
      if ($this->id === Constants::DEFAULT_ID) {
        return;
      }

      ?>
        <a href="<?= $this->getTabUrl(Game::DRIVER_CATEGORIES_TAB) ?>"
           class="nav-tab <?= $this->getActiveCssClass(Game::DRIVER_CATEGORIES_TAB) ?>"><?= esc_html__('Driver Categories', 'sim-league-toolkit') ?></a>
      <?php
    }

    public function theGeneralTab(): void { ?>
        <a href="<?= $this->getTabUrl() ?>"
           class="nav-tab <?= $this->getActiveCssClass('') ?>"><?= esc_html__('General', 'sim-league-toolkit') ?></a>
      <?php
    }

    public function theTabContent(): void {
      $this->tab->render();
    }

    private function getActiveCssClass(string $tabName = ''): string {
      return $this->currentTab === $tabName ? 'nav-tab-active' : '';
    }


    private function getTabUrl(string $tabName = ''): string {
      $urlParams = array(
        QueryParamNames::ID => $this->id
      );

      if (!empty($tabName)) {
        $urlParams[QueryParamNames::TAB] = $tabName;
      }

      return UrlBuilder::getAdminPageAbsoluteUrl(AdminPageSlugs::GAME, $urlParams);
    }

    private function initialiseState(): void {
      $this->id = $this->getIdFromUrl();
      $this->currentTab = $this->getTabFromUrl();
      $this->game = Game::get($this->id) ?? new Game();
      $this->prepareTabController();
    }

    private function prepareTabController(): void {
      $this->tab = match ($this->currentTab) {
        Game::CAR_CLASSES_TAB => new GameCarClassesTab($this->game),
        Game::DRIVER_CATEGORIES_TAB => new GameDriverCategoriesTab($this->game),
        Game::CARS_TAB => new GameCarsTab($this->game),
        Game::TRACKS_TAB => new GameTracksTab($this->game),
        default => new GameDetailsTab($this->game),
      };
    }

    protected function handleGet(): void {
      $this->initialiseState();
    }

    protected function handlePost(): void {
      $this->initialiseState();
    }

  }