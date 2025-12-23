<?php

    namespace SLTK\Pages\Championships;

    use Exception;
    use SLTK\Pages\AdminPage;

    class ChampionshipAdminPage implements AdminPage {
        private ChampionshipAdminPageController $controller;

        public function __construct() {
            $this->controller = new ChampionshipAdminPageController();
        }

        /**
         * @throws Exception
         */
        public function render(): void {
            ?>
            <div class="wrap">
                <h1><?= esc_html__('Championship Details', 'sim-league-toolkit') ?></h1>
                <nav class='nav-tab-wrapper'>
                    <?php
                        $this->controller->theGeneralTab();
                        $this->controller->theBannerImageTab();
                              $this->controller->theEventClassesTab();
                        //      $this->controller->theDriverCategoriesTab();
                        //      $this->controller->theCarsTab();
                        //      $this->controller->theTracksTab();
                    ?>
                </nav>
                <div class='tab-content'>
                    <?php
                        $this->controller->theTabContent();
                    ?>
                </div>
                <p>
                    <?php
                        $this->controller->theBackButton();
                    ?>
                </p>
            </div>
            <?php
        }
    }