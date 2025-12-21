<?php

    namespace SLTK\Pages\Championships;

    use Exception;
    use SLTK\Pages\AdminPage;

    class CreateChampionshipPage implements AdminPage {
        private CreateChampionshipPageController $controller;

        public function __construct() {
            $this->controller = new CreateChampionshipPageController();
        }

        /**
         * @throws Exception
         */
        public function render(): void { ?>
            <div class='wrap'>
                <h1><?= esc_html__('New Championship', 'sim-league-toolkit') ?></h1>
                <?php
                    $this->controller->theNewChampionshipMessage();
                ?>
                <form method='post'>
                    <?php
                        $this->controller->theHiddenFields();
                    ?>
                    <table class='form-table'>
                        <?php
                            $this->controller->theGameSelector();
                            $this->controller->thePlatformSelector();
                            $this->controller->theNameField();
                            $this->controller->theDescriptionField();
                            $this->controller->theStartDateField();
                            $this->controller->theRuleSetSelector();
                            $this->controller->theScoringSetSelector();
                            $this->controller->theResultsToDiscardField();
                            $this->controller->theChampionshipTypeField();
                            $this->controller->theTrackMasterTrackSelector();
                            $this->controller->theTrackMasterTrackLayoutSelector();
                            $this->controller->theEntryChangeLimitField();
                            $this->controller->theActiveField();
                        ?>
                    </table>
                    <p>
                        <?php
                            $this->controller->theBackButton();
                            $this->controller->theSaveButton();
                        ?>
                    </p>
                </form>
            </div>
            <?php
        }
    }