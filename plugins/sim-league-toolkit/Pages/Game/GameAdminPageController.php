<?php

    namespace SLTK\Pages\Game;

    use Exception;
    use SLTK\Core\AdminPageSlugs;
    use SLTK\Core\Constants;
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
                <a href="<?= UrlBuilder::getAdminPageAbsoluteUrl(AdminPageSlugs::GAMES) ?>"
                   class='button button-secondary'><?= esc_html__('Back to Games', 'sim-league-toolkit') ?></a>
            </p>
            <?php
        }

        public function theCarClassesTab(): void {
            if ($this->id === Constants::DEFAULT_ID) {
                return;
            }

            ?>
            <a href="<?= $this->getTabUrl(AdminPageSlugs::GAME, $this->id, Game::CAR_CLASSES_TAB) ?>"
               class="nav-tab <?= $this->getActiveCssClass($this->currentTab, Game::CAR_CLASSES_TAB) ?>"><?= esc_html__('Car Classes', 'sim-league-toolkit') ?></a>
            <?php
        }

        public function theCarsTab(): void {
            if ($this->id === Constants::DEFAULT_ID) {
                return;
            }

            ?>
            <a href="<?= $this->getTabUrl(AdminPageSlugs::GAME, $this->id, Game::CARS_TAB) ?>"
               class="nav-tab <?= $this->getActiveCssClass($this->currentTab, Game::CARS_TAB) ?>"><?= esc_html__('Cars', 'sim-league-toolkit') ?></a>
            <?php
        }

        public function theDriverCategoriesTab(): void {
            if ($this->id === Constants::DEFAULT_ID) {
                return;
            }

            ?>
            <a href="<?= $this->getTabUrl(AdminPageSlugs::GAME, $this->id, Game::DRIVER_CATEGORIES_TAB) ?>"
               class="nav-tab <?= $this->getActiveCssClass($this->currentTab, Game::DRIVER_CATEGORIES_TAB) ?>"><?= esc_html__('Driver Categories', 'sim-league-toolkit') ?></a>
            <?php
        }

        public function theGeneralTab(): void { ?>
            <a href="<?= $this->getTabUrl(AdminPageSlugs::GAME, $this->id) ?>"
               class="nav-tab <?= $this->getActiveCssClass($this->currentTab, '') ?>"><?= esc_html__('General', 'sim-league-toolkit') ?></a>
            <?php
        }

        /**
         * @throws Exception
         */
        public function theTabContent(): void {
            $this->tab->render();
        }

        public function theTracksTab(): void {
            if ($this->id === Constants::DEFAULT_ID) {
                return;
            }

            ?>
            <a href="<?= $this->getTabUrl(AdminPageSlugs::GAME, $this->id, Game::TRACKS_TAB) ?>"
               class="nav-tab <?= $this->getActiveCssClass($this->currentTab, Game::TRACKS_TAB) ?>"><?= esc_html__('Tracks', 'sim-league-toolkit') ?></a>
            <?php
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