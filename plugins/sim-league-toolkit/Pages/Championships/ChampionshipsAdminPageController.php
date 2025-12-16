<?php

    namespace SLTK\Pages\Championships;

    use Exception;
    use SLTK\Core\CommonFieldNames;
    use SLTK\Core\Constants;
    use SLTK\Domain\Championship;
    use SLTK\Pages\ControllerBase;

    class ChampionshipsAdminPageController extends ControllerBase {

        public function theTable(): void {
            $table = new ChampionshipsTable();
            $table->prepare_items();
            $table->display();
        }

        /**
         * @throws Exception
         */
        private function confirmDelete(): void {
            $championshipId = $this->getIdFromUrl();
            if ($championshipId !== Constants::DEFAULT_ID) {
                $championshipToDelete = Championship::get($championshipId);
                ?>
                <div class="notice notice-warning">
                    <p>
                        <?php
                            echo esc_html__('This will delete all data related to the championship including events. Are you sure you want to delete', 'sim-league-toolkit') . ' ' . $championshipToDelete->getName();
                        ?>
                    </p>
                    <form method="POST">
                        <input type="hidden" name="<?= CommonFieldNames::CHAMPIONSHIP_ID ?>"
                               value="<?= $championshipId ?>"/>
                        <input type="submit"
                               name="<?= CommonFieldNames::CONFIRM_DELETE ?>"
                               class="button button-primary button-small"
                               value="<?= esc_html__('Yes', 'sim-league-toolkit') ?>"/>
                        <input type="submit" name="<?= CommonFieldNames::CANCEL_DELETE ?>"
                               class="button button-cancel button-small"
                               value="<?= esc_html__('No', 'sim-league-toolkit') ?>"/>
                    </form>
                </div>
                <?php
            }
        }

        /**
         * @throws Exception
         */
        protected function handleGet(): void {
            $action = $this->getActionFromUrl();
            if ($action === Constants::ACTION_DELETE) {
                $this->confirmDelete();
            }
        }

        /**
         * @throws Exception
         */
        protected function handlePost(): void {
            $shouldDelete = $this->postContainsField(CommonFieldNames::CONFIRM_DELETE);
            if ($shouldDelete) {
                $championshipIdToDelete = $this->getSanitisedFieldFromPost(CommonFieldNames::CHAMPIONSHIP_ID);
                Championship::delete($championshipIdToDelete); ?>
                <div class='notice notice-success'>
                    <?= esc_html__('The Championship was deleted successfully.', 'sim-league-toolkit') ?>
                </div>
                <?php
            }

        }
    }