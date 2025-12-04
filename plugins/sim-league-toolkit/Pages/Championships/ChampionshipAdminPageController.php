<?php

    namespace SLTK\Pages\Championships;

    use SLTK\Core\AdminPageSlugs;
    use SLTK\Pages\ControllerBase;

    class ChampionshipAdminPageController extends ControllerBase {

        public function theBackButton(): void { ?>
            <br/>
            <p>
                <a href="<?= get_admin_url() . 'admin.php?page=' . AdminPageSlugs::CHAMPIONSHIPS ?>"
                   class='button button-secondary'><?= esc_html__('Back to Championships', 'sim-league-toolkit') ?></a>
            </p>
            <?php
        }

        protected function handleGet(): void {

        }

        protected function handlePost(): void {

        }
    }