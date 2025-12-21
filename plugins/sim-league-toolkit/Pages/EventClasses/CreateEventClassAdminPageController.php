<?php

    namespace SLTK\Pages\EventClasses;

    use Exception;
    use SLTK\Components\CarClassSelectorComponent;
    use SLTK\Components\CarSelectorComponent;
    use SLTK\Components\DriverCategorySelectorComponent;
    use SLTK\Components\GameSelectorComponent;
    use SLTK\Components\SelectorComponentConfig;
    use SLTK\Core\AdminPageSlugs;
    use SLTK\Core\CommonFieldNames;
    use SLTK\Core\Constants;
    use SLTK\Core\HtmlTagProvider;
    use SLTK\Core\HtmlTagProviderInputConfig;
    use SLTK\Core\QueryParamNames;
    use SLTK\Core\UrlBuilder;
    use SLTK\Domain\EventClass;
    use SLTK\Pages\ControllerBase;

    class CreateEventClassAdminPageController extends ControllerBase {

        private CarClassSelectorComponent $carClassSelectorComponent;
        private CarSelectorComponent $carSelectorComponent;
        private DriverCategorySelectorComponent $driverCategorySelectorComponent;
        private EventClass $eventClass;
        private GameSelectorComponent $gameSelectorComponent;

        public function theBackButton(): void {
            $url = UrlBuilder::getAdminPageRelativeUrl(AdminPageSlugs::EVENT_CLASSES);
            ?>
            <a class='button button-secondary' href='<?= $url ?>'
               title='<?= esc_html__('Back To Event Classes', 'sim-league-toolkit') ?>'><?= esc_html__('Back To Event Classes', 'sim-league-toolkit') ?></a>
            <?php
        }

        /**
         * @throws Exception
         */
        public function theCarClassSelector(): void {
            if ($this->eventClass->getGameId() === Constants::DEFAULT_ID) {
                return;
            }

            $error = $this->getError(EventClass::CAR_CLASS_FIELD_NAME);
            ?>
            <tr>
                <th scope='row'>
                    <label class='form-label'
                           for='<?= CarClassSelectorComponent::FIELD_ID ?>' <?= HtmlTagProvider::errorLabelClass($error) ?>
                           title='<?= $this->carClassSelectorComponent->getTooltip() ?>'>
                        <?= esc_html__('Car Class', 'sim-league-toolkit') ?></label></th>
                <td>
                    <?php
                        $this->carClassSelectorComponent->render();
                        HtmlTagProvider::theValidationError($error);
                    ?>
                </td>
            </tr>
            <?php
        }

        /**
         * @throws Exception
         */
        public function theDriverCategorySelector(): void {
            if ($this->eventClass->getGameId() === Constants::DEFAULT_ID) {
                return;
            }

            $error = $this->getError(EventClass::DRIVER_CATEGORY_FIELD_NAME);
            ?>
            <tr>
                <th scope='row'>
                    <label class='form-label'
                           for='<?= DriverCategorySelectorComponent::FIELD_ID ?>' <?= HtmlTagProvider::errorLabelClass($error) ?>
                           title='<?= $this->driverCategorySelectorComponent->getTooltip() ?>'>
                        <?= esc_html__('Driver Category', 'sim-league-toolkit') ?></label></th>
                <td>
                    <?php
                        $this->driverCategorySelectorComponent->render();
                        HtmlTagProvider::theValidationError($error);
                    ?>
                </td>
            </tr>
            <?php
        }

        public function theGameSelector(): void {
            $error = $this->getError(EventClass::GAME_ID_FIELD_NAME);
            ?>
            <tr>
                <th scope='row'>
                    <label class='form-label'
                           for='<?= GameSelectorComponent::FIELD_ID ?>' <?= HtmlTagProvider::errorLabelClass($error) ?>
                           title='<?= $this->gameSelectorComponent->getTooltip() ?>'>
                        <?= esc_html__('Game', 'sim-league-toolkit') ?></label></th>
                <td>
                    <?php
                        $this->gameSelectorComponent->render();
                        HtmlTagProvider::theValidationError($error);
                    ?>
                </td>
            </tr>
            <?php
        }

        public function theHiddenFields(): void {
            $this->theNonce();
            HtmlTagProvider::theHiddenField(EventClass::GAME_ID_FIELD_NAME, $this->eventClass->getGameId());

        }

        public function theIsSingleCarClassField(): void {
            if ($this->eventClass->getGameId() === Constants::DEFAULT_ID) {
                return;
            }

            $error = $this->getError(EventClass::IS_SINGLE_CAR_CLASS_FIELD_NAME);
            $config = new HtmlTagProviderInputConfig(EventClass::IS_SINGLE_CAR_CLASS_FIELD_NAME,
                    esc_html__('Is Single Car Class', 'sim-league-toolkit'),
                    true, $error, '', 'checkbox');

            $config->checked = $this->eventClass->getIsSingleCarClass();
            $config->submitOnChange = true;

            HtmlTagProvider::theAdminInputField($config);
        }

        public function theNameField(): void {
            if ($this->eventClass->getGameId() === Constants::DEFAULT_ID) {
                return;
            }

            HtmlTagProvider::theAdminTextInput(esc_html__('Name', 'sim-league-toolkit'),
                    EventClass::NAME_FIELD_NAME,
                    $this->eventClass->getName() ?? '',
                    $this->getError(EventClass::NAME_FIELD_NAME),
                    esc_html__('The name of the event class', 'sim-league-toolkit'));
        }

        public function theNewEventClassMessage(): void {
            ?>
            <p>
                <?= esc_html__('To get started creating a new event class select a game from the list, then use the form to enter the remaining details.  Once the event class is created you will not be able to change the game.', 'sim-league-toolkit') ?>
            </p>
            <?php
        }

        public function theSaveButton(): void {
            if ($this->eventClass->getGameId() === Constants::DEFAULT_ID) {
                return;
            }
            ?>
            <input type='submit' class='button button-primary' id='<?= CommonFieldNames::SAVE_BUTTON ?>'
                   name='<?= CommonFieldNames::SAVE_BUTTON ?>' value='<?= esc_html__('Save', 'sim-league-toolkit') ?>'
                   title='<?= esc_html__('Save the new event class.', 'sim-league-toolkit') ?>'/>
            <?php
        }

        /**
         * @throws Exception
         */
        public function theSingleCarSelector(): void {
            if ($this->eventClass->getGameId() === Constants::DEFAULT_ID || !$this->eventClass->getIsSingleCarClass()) {
                return;
            }

            $error = $this->getError(EventClass::SINGLE_CAR_ID_FIELD_NAME);
            ?>
            <tr>
                <th scope='row'>
                    <label class='form-label'
                           for='<?= CarSelectorComponent::FIELD_ID ?>' <?= HtmlTagProvider::errorLabelClass($error) ?>
                           title='<?= $this->carSelectorComponent->getTooltip() ?>'>
                        <?= esc_html__('Single Car', 'sim-league-toolkit') ?></label></th>
                <td>
                    <?php
                        $this->carSelectorComponent->render();
                        HtmlTagProvider::theValidationError($error);
                    ?>
                </td>
            </tr>
            <?php
        }

        private function initialiseStateFromPost(): void {
            $gameId = $this->gameSelectorComponent->getValue();
            $carClass = $this->carClassSelectorComponent->getValue();
            $driverCategory = $this->driverCategorySelectorComponent->getValue();
            $isSingleCarClass = $this->postContainsField(EventClass::IS_SINGLE_CAR_CLASS_FIELD_NAME);
            $name = $this->getSanitisedFieldFromPost(EventClass::NAME_FIELD_NAME, '');
            $singleCarId = $this->carSelectorComponent->getValue();


            $this->eventClass->setGameId($gameId);
            $this->eventClass->setCarClass($carClass);
            $this->eventClass->setDriverCategoryId($driverCategory);
            $this->eventClass->setIsSingleCarClass($isSingleCarClass);
            $this->eventClass->setName($name);
            $this->eventClass->setSingleCarId($singleCarId > 0? $singleCarId : null);

            if ($gameId != Constants::DEFAULT_ID) {
                $this->carClassSelectorComponent->setGameId($gameId);
                $this->carSelectorComponent->setGameId($gameId);
            }
        }

        private function initialiseState(): void {
            $this->gameSelectorComponent = new GameSelectorComponent(new SelectorComponentConfig(true, true, esc_html__('The game the event class applies to', 'sim-league-toolkit')));
            $this->carClassSelectorComponent = new CarClassSelectorComponent(new SelectorComponentConfig(false, true, esc_html__('The car class from the game the event class applies to', 'sim-league-toolkit')));
            $this->carSelectorComponent = new CarSelectorComponent(new SelectorComponentConfig(false, false, esc_html__('The single car from the game the event class applies to', 'sim-league-toolkit')));
            $this->driverCategorySelectorComponent = new DriverCategorySelectorComponent(new SelectorComponentConfig(false, false, esc_html__('The driver category the event class applies to', 'sim-league-toolkit')));

            $this->eventClass = new EventClass();
        }

        private function processCarClassSelection(): void {
            $carClass = $this->carClassSelectorComponent->getValue();
            if(empty($carClass)) {
                return;
            }

            $this->carSelectorComponent->setCarClass($carClass);
        }

        /**
         * @throws Exception
         */
        private function processForm(): void {
            $validationResult = $this->eventClass->validate();
            if (!$validationResult->success) {
                $this->errors = $validationResult->validationErrors;

                return;
            }

            if ($this->eventClass->save()) {
                $urlParameters = [
                        QueryParamNames::ID => $this->eventClass->id,
                        QueryParamNames::ACTION => Constants::ACTION_EDIT
                ];
                $url = UrlBuilder::getAdminPageAbsoluteUrl(AdminPageSlugs::EVENT_CLASSES, $urlParameters);
                $this->theSuccessMessage($this->eventClass->getName() . ' has been created successfully, please wait...', $url);
                $this->theRedirectScript($url, 2);
            }
        }

        private function processGameSelection(): void {
            $gameId = (int)$this->gameSelectorComponent->getValue();
            if ($gameId === Constants::DEFAULT_ID) {
                HtmlTagProvider::theErrorMessage(__('You must select a game.', 'sim-league-toolkit'));

                return;
            }


            $this->eventClass->setGameId($gameId);
            $this->carClassSelectorComponent->setGameId($gameId);
            $this->carSelectorComponent->setGameId($gameId);
        }

        protected function handleGet(): void {
            $this->initialiseState();
        }

        /**
         * @throws Exception
         */
        protected function handlePost(): void {
            $this->initialiseState();
            $this->initialiseStateFromPost();

            if (!$this->postContainsField(CommonFieldNames::SAVE_BUTTON)) {
                $this->processGameSelection();
                $this->processCarClassSelection();

                return;
            }

            $this->processForm();
        }
    }