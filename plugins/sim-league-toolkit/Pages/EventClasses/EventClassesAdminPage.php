<?php

    namespace SLTK\Pages\EventClasses;

    use SLTK\Pages\AdminPage;

    class EventClassesAdminPage implements AdminPage {
        private EventClassesAdminPageController $controller;

        public function __construct() {
            $this->controller = new EventClassesAdminPageController();
        }

        public function render(): void {
            ?>
            <div class='wrap'>
                <h1><?= esc_html__('Event Classes', 'sim-league-toolkit') ?></h1>
                <p>
                    <?= esc_html__('Event Classes allow you to support multi-class racing in championships and one off events.  Several built-in event classes are provided, but you can add your own using the form below.', 'sim-league-toolkit') ?>
                </p>
                <?php
                    $this->controller->theTable();
                ?>
            </div>
            <?php
        }
    }