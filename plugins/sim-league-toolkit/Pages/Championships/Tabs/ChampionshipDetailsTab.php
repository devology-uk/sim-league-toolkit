<?php

    namespace SLTK\Pages\Championships\Tabs;

    use Exception;
    use SLTK\Pages\AdminTab;

    class ChampionshipDetailsTab implements AdminTab {

        private ChampionshipDetailsTabController $controller;

        public function __construct() {
            $this->controller = new ChampionshipDetailsTabController();
        }

        /**
         * @throws Exception
         */
        public function render(): void { ?>
            <div class='wrap'>
            <form method='post' enctype='multipart/form-data'>
                <?php
                    $this->controller->theHiddenFields();
                ?>
                <table class='form-table'>
                    <?php
                        $this->controller->theGameField();
                        $this->controller->thePlatformField();
                        $this->controller->theNameField();
                        $this->controller->theDescriptionField();
                        $this->controller->theStartDateField();
                        $this->controller->theRuleSetSelector();
                        $this->controller->theScoringSetSelector();
                        $this->controller->theResultsToDiscardField();
                        $this->controller->theEntryChangeLimitField();
                        $this->controller->theChampionshipTypeField();
                        $this->controller->theTrackMasterTrackSelector();
                        $this->controller->theTrackMasterTrackLayoutSelector();
                        $this->controller->theActiveField();
                    ?>
                </table>
                <p>
                    <?php
                        $this->controller->theSaveButton();
                    ?>
                </p>
            </form>
            <?php

        }
    }