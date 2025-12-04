<?php

    namespace SLTK\Pages\Events;

    use SLTK\Core\AdminPageSlugs;
    use SLTK\Pages\ControllerBase;

    class EventAdminPageController extends ControllerBase {

        public function theBackButton(): void { ?>
            <br/>
            <p>
                <a href="<?= get_admin_url() . 'admin.php?page=' . AdminPageSlugs::EVENTS ?>"
                   class='button button-secondary'><?= esc_html__('Back to Events', 'sim-league-toolkit') ?></a>
            </p>
            <?php
        }

        protected function handleGet(): void {

        }

        protected function handlePost(): void {

        }
    }