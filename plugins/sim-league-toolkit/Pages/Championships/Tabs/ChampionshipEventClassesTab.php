<?php

    namespace SLTK\Pages\Championships\Tabs;

    use SLTK\Pages\AdminTab;

    class ChampionshipEventClassesTab implements AdminTab {

        private ChampionshipEventClassesTabController $controller;

        public function __construct() {
            $this->controller = new ChampionshipEventClassesTabController();
        }

        public function render(): void { ?>
            <div class='wrap'>
            </div>
            <?php
        }
    }