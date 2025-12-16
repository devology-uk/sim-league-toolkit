<?php

    namespace SLTK\Pages\Championships;

    use Exception;
    use SLTK\Core\AdminPageSlugs;
    use SLTK\Core\CommonFieldNames;
    use SLTK\Core\Constants;
    use SLTK\Core\UrlBuilder;
    use SLTK\Domain\Championship;
    use SLTK\Pages\Championships\Tabs\ChampionshipDetailsTab;
    use SLTK\Pages\ControllerBase;

    class ChampionshipAdminPageController extends ControllerBase {

        private string $currentTab = '';
        private int $championshipId = Constants::DEFAULT_ID;
        private ChampionshipDetailsTab $tab;

        public function theBackButton(): void { ?>
            <br/>
            <p>
                <a href="<?= UrlBuilder::getAdminPageAbsoluteUrl(AdminPageSlugs::CHAMPIONSHIPS) ?>"
                   class='button button-secondary'><?= esc_html__('Back to Championships', 'sim-league-toolkit') ?></a>
            </p>
            <?php
        }

        /**
         * @throws Exception
         */
        public function theTabContent(): void {
            $this->tab->render();
        }

        public function theGeneralTab(): void { ?>
            <a href="<?= $this->getTabUrl(AdminPageSlugs::CHAMPIONSHIP, '', $this->championshipId) ?>"
               class="nav-tab <?= $this->getActiveCssClass($this->currentTab) ?>"><?= esc_html__('General', 'sim-league-toolkit') ?></a>
            <?php
        }
        private function initialiseState(): void {
            $this->championshipId = $this->getIdFromUrl();
            $this->currentTab = $this->getTabFromUrl();
            $this->prepareTabController();
        }

        private function prepareTabController(): void {
            $this->tab = match ($this->currentTab) {
//                Game::CAR_CLASSES_TAB => new GameCarClassesTab($this->game),
//                Game::DRIVER_CATEGORIES_TAB => new GameDriverCategoriesTab($this->game),
//                Game::CARS_TAB => new GameCarsTab($this->game),
//                Game::TRACKS_TAB => new GameTracksTab($this->game),
                default => new ChampionshipDetailsTab(),
            };
        }

        protected function handleGet(): void {
            $this->initialiseState();
        }

        protected function handlePost(): void {
            $this->initialiseState();
        }
    }