<?php

    namespace SLTK\Pages\EventClasses;

    use Exception;
    use SLTK\Components\CarClassSelectorComponent;
    use SLTK\Components\CarSelectorComponent;
    use SLTK\Components\DriverCategorySelectorComponent;
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

    class EventClassAdminPageController extends ControllerBase {

        private CarClassSelectorComponent $carClassSelectorComponent;
        private CarSelectorComponent $carSelectorComponent;
        private DriverCategorySelectorComponent $driverCategorySelectorComponent;
        private ?EventClass $eventClass;
        private int $eventClassId = Constants::DEFAULT_ID;

        public function getClassName(): string {
            return $this->eventClass->getName();
        }

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
            if ($this->eventClass->getIsBuiltIn()) {
                HtmlTagProvider::theAdminTextDisplay(esc_html__('Car Class', 'sim-league-toolkit'), $this->eventClass->getCarClass());

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
            if ($this->eventClass->getIsBuiltIn()) {
                HtmlTagProvider::theAdminTextDisplay(esc_html__('Driver Category', 'sim-league-toolkit'), $this->eventClass->getDriverCategory());

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

        public function theGameField(): void {
            HtmlTagProvider::theAdminTextDisplay(esc_html__('Game', 'sim-league-toolkit'), $this->eventClass->getGame());
        }

        public function theHiddenFields(): void {
            if ($this->eventClass->getIsBuiltIn()) {
                return;
            }

            $this->theNonce();
            HtmlTagProvider::theHiddenField(EventClass::GAME_ID_FIELD_NAME, $this->eventClass->getGameId());
        }

        public function theIntroduction(): void {
            ?>
            <p>
                <?php
                    echo esc_html__('The details of the event class are shown below.', 'sim-league-toolkit') . ' ';
                    if ($this->eventClass->getIsBuiltIn()) {
                        echo esc_html__('The event class is built in, which means it cannot be changed.', 'sim-league-toolkit');
                    } else {
                        echo esc_html__('Use the form below to make changes then activate the Save button.', 'sim-league-toolkit');
                    }

                ?>
            </p>
            <?php
        }


        public function theIsSingleCarClassField(): void {
            if ($this->eventClass->getIsBuiltIn()) {
                HtmlTagProvider::theAdminTextDisplay(esc_html__('Is Single Car Class', 'sim-league-toolkit'), $this->eventClass->getIsSingleCarClass() ? esc_html('Yes') : esc_html('No'));

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
            if ($this->eventClass->getIsBuiltIn()) {
                HtmlTagProvider::theAdminTextDisplay(esc_html__('Name', 'sim-league-toolkit'), $this->eventClass->getName());

                return;
            }

            HtmlTagProvider::theAdminTextInput(esc_html__('Name', 'sim-league-toolkit'),
                    EventClass::NAME_FIELD_NAME,
                    $this->eventClass->getName(),
                    $this->getError(EventClass::NAME_FIELD_NAME)
            );
        }

        public function theSaveButton(): void {
            if ($this->eventClass->getIsBuiltIn()) {
                return;
            }
            ?>
            <input type='submit' class='button button-primary' id='<?= CommonFieldNames::SAVE_BUTTON ?>'
                   name='<?= CommonFieldNames::SAVE_BUTTON ?>' value='<?= esc_html__('Save', 'sim-league-toolkit') ?>'
                   title='<?= esc_html__('Save changes to the event class.', 'sim-league-toolkit') ?>'/>
            <?php
        }

        /**
         * @throws Exception
         */
        public function theSingleCarSelector(): void {
            if (!$this->eventClass->getIsSingleCarClass()) {
                return;
            }

            if ($this->eventClass->getIsBuiltIn()) {
                HtmlTagProvider::theAdminTextDisplay(esc_html__('Single Car', 'sim-league-toolkit'), $this->eventClass->getSingleCarName());

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

        /**
         * @throws Exception
         */
        private function initialiseState(): void {
            $this->eventClassId = $this->getIdFromUrl();
            $this->eventClass = EventClass::get($this->eventClassId);

            if ($this->eventClass->getIsBuiltIn()) {
                return;
            }

            $this->carClassSelectorComponent = new CarClassSelectorComponent(new SelectorComponentConfig(false, true, esc_html__('The car class from the game the event class applies to', 'sim-league-toolkit')));
            $this->carSelectorComponent = new CarSelectorComponent(new SelectorComponentConfig(false, false, esc_html__('The single car from the game the event class applies to', 'sim-league-toolkit')));
            $this->driverCategorySelectorComponent = new DriverCategorySelectorComponent(new SelectorComponentConfig(false, false, esc_html__('The driver category the event class applies to', 'sim-league-toolkit')));

            $this->carClassSelectorComponent->setGameId($this->eventClass->getGameId());
            $this->carClassSelectorComponent->setValue($this->eventClass->getCarClass());

            $this->carSelectorComponent->setGameId($this->eventClass->getGameId());
            $this->carSelectorComponent->setCarClass($this->eventClass->getCarClass());
            $this->carSelectorComponent->setValue($this->eventClass->getSingleCarId());

            $this->driverCategorySelectorComponent->setValue($this->eventClass->getDriverCategoryId());
        }

        private function initialiseStateFromPost(): void {
            $carClass = $this->carClassSelectorComponent->getValue();
            $driverCategory = $this->driverCategorySelectorComponent->getValue();
            $isSingleCarClass = $this->postContainsField(EventClass::IS_SINGLE_CAR_CLASS_FIELD_NAME);
            $name = $this->getSanitisedFieldFromPost(EventClass::NAME_FIELD_NAME, '');
            $singleCarId = $this->carSelectorComponent->getValue();


            $this->eventClass->setCarClass($carClass);
            $this->eventClass->setDriverCategoryId($driverCategory);
            $this->eventClass->setIsSingleCarClass($isSingleCarClass);
            $this->eventClass->setName($name);
            $this->eventClass->setSingleCarId($singleCarId > 0? $singleCarId : null);
        }

        /**
         * @throws Exception
         */
        protected function handleGet(): void {
            $this->initialiseState();
        }

        /**
         * @throws Exception
         */
        protected function handlePost(): void {
            if (!$this->validateNonce()) {
                return;
            }

            $this->initialiseState();
            $this->initialiseStateFromPost();

            if (!$this->postContainsField(CommonFieldNames::SAVE_BUTTON)) {
                $this->processCarClassSelection();

                return;
            }

            $this->processForm();
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
                $this->theSuccessMessage(esc_html__('Changes to') . ' ' .  $this->eventClass->getName() . ' ' . esc_html__('have been saved successfully.', 'sim-league-toolkit'));
            }
        }
    }