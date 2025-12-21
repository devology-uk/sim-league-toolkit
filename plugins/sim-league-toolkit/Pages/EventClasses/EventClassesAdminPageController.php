<?php

    namespace SLTK\Pages\EventClasses;

    use Exception;
    use SLTK\Core\AdminPageSlugs;
    use SLTK\Core\CommonFieldNames;
    use SLTK\Core\Constants;
    use SLTK\Core\QueryParamNames;
    use SLTK\Core\UrlBuilder;
    use SLTK\Domain\EventClass;
    use SLTK\Pages\ControllerBase;

    class EventClassesAdminPageController extends ControllerBase {

        /**
         * @return void
         */
        public function redirectToCleanTable(): void {
            $page = $this->getPageFromUrl();
            $queryParams = [];

            if (!empty($page)) {
                $queryParams[QueryParamNames::PAGE] = $page;
            }

            $url = UrlBuilder::getAdminPageAbsoluteUrl(AdminPageSlugs::EVENT_CLASSES, $queryParams);
            $this->theRedirectScript($url, 2);
        }

        public function theTable(): void {
            $table = new EventClassesTable();
            $table->prepare_items();
            $table->display();
        }

        /**
         * @throws Exception
         */
        private function confirmDelete(): void {
            $eventClassId = $this->getIdFromUrl();
            if ($eventClassId !== Constants::DEFAULT_ID) {
                $eventClassToDelete = EventClass::get($eventClassId);
                ?>
                <div class="notice notice-warning">
                    <p>
                        <?php
                            echo esc_html__('Are you sure you want to delete ', 'sim-league-toolkit') . ' ' . $eventClassToDelete->getName();
                        ?>
                    </p>
                    <form method="POST">
                        <input type="hidden" name="<?= EventClass::EVENT_CLASS_ID_FIELD_NAME ?>"
                               value="<?= $eventClassId ?>"/>
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

        private function theCannotDeleteMessage(string $itemName): void {
            ?>
            <div class="notice notice-error">
                <p>
                    <?php
                        echo $itemName . ' ' . esc_html__('Cannot be deleted because it either built-in or in use.', 'sim-league-toolkit');
                    ?>
                </p>
            </div>
            <?php
            $this->redirectToCleanTable();
        }

        /**
         * @throws Exception
         */
        protected function handleGet(): void {
            $action = $this->getActionFromUrl();
            if ($action === Constants::ACTION_DELETE) {
                $idToDelete = $this->getIdFromUrl();
                $itemToDelete = EventClass::get($idToDelete);
                if (!$itemToDelete->canDelete()) {
                    $this->theCannotDeleteMessage($itemToDelete->getName());

                    return;
                }
                $this->confirmDelete();
            }
        }

        /**
         * @throws Exception
         */
        protected function handlePost(): void {
            $shouldDelete = $this->postContainsField(CommonFieldNames::CONFIRM_DELETE);
            if ($shouldDelete) {
                $idToDelete = $this->getSanitisedFieldFromPost(EventClass::EVENT_CLASS_ID_FIELD_NAME);
                EventClass::delete($idToDelete); ?>
                <div class='notice notice-success'>
                    <?= esc_html__('The Event Class was deleted successfully...', 'sim-league-toolkit') ?>
                </div>
                <?php
                $this->redirectToCleanTable();
            }
        }
    }