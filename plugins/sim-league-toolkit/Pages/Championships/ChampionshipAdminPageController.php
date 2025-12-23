<?php

    namespace SLTK\Pages\Championships;

    use Exception;
    use SLTK\Core\AdminPageSlugs;
    use SLTK\Core\Constants;
    use SLTK\Core\UrlBuilder;
    use SLTK\Domain\Championship;
    use SLTK\Pages\Championships\Tabs\ChampionshipBannerImageTab;
    use SLTK\Pages\Championships\Tabs\ChampionshipDetailsTab;
    use SLTK\Pages\Championships\Tabs\ChampionshipEventClassesTab;
    use SLTK\Pages\ControllerBase;

    class ChampionshipAdminPageController extends ControllerBase {

        private int $championshipId = Constants::DEFAULT_ID;
        private string $currentTab = '';
        private ChampionshipDetailsTab|ChampionshipBannerImageTab|ChampionshipEventClassesTab $tab;

        public function theBackButton(): void { ?>
            <br/>
            <p>
                <a href="<?= UrlBuilder::getAdminPageAbsoluteUrl(AdminPageSlugs::CHAMPIONSHIPS) ?>"
                   class='button button-secondary'><?= esc_html__('Back to Championships', 'sim-league-toolkit') ?></a>
            </p>
            <?php
        }

      public function theEventClassesTab(): void {    ?>
          <a href="<?= $this->getTabUrl(AdminPageSlugs::CHAMPIONSHIP, Championship::EVENT_CLASSES_TAB, $this->championshipId) ?>"
             class="nav-tab <?= $this->getActiveCssClass($this->currentTab, Championship::EVENT_CLASSES_TAB) ?>"><?= esc_html__('Event Classes', 'sim-league-toolkit') ?></a>
          <?php
      }

      public function theGeneralTab(): void { ?>
            <a href="<?= $this->getTabUrl(AdminPageSlugs::CHAMPIONSHIP, '', $this->championshipId) ?>"
               class="nav-tab <?= $this->getActiveCssClass($this->currentTab) ?>"><?= esc_html__('General', 'sim-league-toolkit') ?></a>
            <?php
        }

        public function theBannerImageTab(): void { ?>
            <a href="<?= $this->getTabUrl(AdminPageSlugs::CHAMPIONSHIP, Championship::BANNER_IMAGE_TAB, $this->championshipId) ?>"
               class="nav-tab <?= $this->getActiveCssClass($this->currentTab, Championship::BANNER_IMAGE_TAB) ?>"><?= esc_html__('Banner Image', 'sim-league-toolkit') ?></a>
            <?php
        }

        /**
         * @throws Exception
         */
        public function theTabContent(): void {
            $this->tab->render();
        }

        private function initialiseState(): void {
            $this->championshipId = $this->getIdFromUrl();
            $this->currentTab = $this->getTabFromUrl();
            $this->prepareTabController();
        }

        private function prepareTabController(): void {
            $this->tab = match ($this->currentTab) {
                Championship::BANNER_IMAGE_TAB => new ChampionshipBannerImageTab(),
                Championship::EVENT_CLASSES_TAB => new ChampionshipEventClassesTab(),
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