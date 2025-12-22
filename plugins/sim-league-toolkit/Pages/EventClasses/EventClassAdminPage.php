<?php

    namespace SLTK\Pages\EventClasses;

    use Exception;
    use SLTK\Pages\AdminPage;

    class EventClassAdminPage implements AdminPage {

        private EventClassAdminPageController $controller;

        public function __construct() {
            $this->controller = new EventClassAdminPageController();
        }

        /**
         * @throws Exception
         */
        public function render(): void {
            ?>
            <div class='wrap'>
                <h1><?= esc_html__('Event Class Details', 'sim-league-toolkit') ?> - <?= $this->controller->getClassName() ?></h1>
                <?php
                    $this->controller->theIntroduction();
                ?>
            <form method='post'>
                <?php
                    $this->controller->theHiddenFields();
                ?>
                <table class='form-table'>
                    <?php
                        $this->controller->theGameField();
                        $this->controller->theNameField();
                        $this->controller->theDriverCategorySelector();
                        $this->controller->theCarClassSelector();
                        $this->controller->theIsSingleCarClassField();
                        $this->controller->theSingleCarSelector();

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