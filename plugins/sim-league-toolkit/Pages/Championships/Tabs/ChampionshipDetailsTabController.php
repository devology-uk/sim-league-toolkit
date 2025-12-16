<?php

    namespace SLTK\Pages\Championships\Tabs;

    use DateTime;
    use Exception;
    use SLTK\Components\RuleSetSelectorComponent;
    use SLTK\Components\ScoringSetSelectorComponent;
    use SLTK\Components\SelectorComponentConfig;
    use SLTK\Components\TrackLayoutSelectorComponent;
    use SLTK\Components\TrackSelectorComponent;
    use SLTK\Core\AdminPageSlugs;
    use SLTK\Core\BannerImageProvider;
    use SLTK\Core\ChampionshipTypes;
    use SLTK\Core\CommonFieldNames;
    use SLTK\Core\Constants;
    use SLTK\Core\HtmlTagProvider;
    use SLTK\Core\HtmlTagProviderInputConfig;
    use SLTK\Core\QueryParamNames;
    use SLTK\Core\UrlBuilder;
    use SLTK\Core\Utility;
    use SLTK\Domain\Championship;
    use SLTK\Domain\Game;
    use SLTK\Pages\ControllerBase;

    class ChampionshipDetailsTabController extends ControllerBase {

        private ?Championship $championship;
        private int $championshipId = Constants::DEFAULT_ID;
        private string $championshipType = ChampionshipTypes::STANDARD;
        private Game $game;
        private RuleSetSelectorComponent $ruleSetSelectorComponent;
        private ScoringSetSelectorComponent $scoringSetSelectorComponent;
        private TrackLayoutSelectorComponent $trackLayoutSelectorComponent;
        private TrackSelectorComponent $trackSelectorComponent;

        public function __construct() {
            parent::__construct();
        }

        public function theActiveField(): void {
            HtmlTagProvider::theAdminCheckboxInput(esc_html__('Active', 'sim-league-toolkit'), Championship::IS_ACTIVE_FIELD_NAME, $this->championship->getIsActive());
        }

        public function theSaveButton(): void {
            ?>
            <input type='submit' class='button button-primary' id='<?= CommonFieldNames::SAVE_BUTTON ?>'
                   name='<?= CommonFieldNames::SAVE_BUTTON ?>' value='<?= esc_html__('Save', 'sim-league-toolkit') ?>'
                   title='<?= esc_html__('Save changes to the championship details.', 'sim-league-toolkit') ?>'/>
            <?php
        }

        public function theChampionshipTypeField(): void {
            ?>
            <tr>
                <th scope='row'>
                    <label class='form-label'
                           for='<?= Championship::TYPE_FIELD_NAME ?>'
                           title='<?= esc_html__('The type of championship', 'sim-league-toolkit') ?>'>
                        <?= esc_html__('Championship Type', 'sim-league-toolkit') ?>
                    </label>
                </th>
                <td>
                    <select id='<?= Championship::TYPE_FIELD_NAME ?>' name='<?= Championship::TYPE_FIELD_NAME ?>'
                            onchange='this.form.submit()'
                            title='<?= esc_html__('The type of championship', 'sim-league-toolkit') ?>'>
                        <option value='<?= ChampionshipTypes::STANDARD ?>' <?= selected($this->championshipType, ChampionshipTypes::STANDARD, false) ?>>
                            <?= esc_html__('Standard', 'sim-league-toolkit') ?>
                        </option>
                        <option value='<?= ChampionshipTypes::STANDARD_NO_ENTRY_CHANGES ?>' <?= selected($this->championshipType, ChampionshipTypes::STANDARD_NO_ENTRY_CHANGES, false) ?>>
                            <?= esc_html__('Standard No Change To Entry Allowed', 'sim-league-toolkit') ?>
                        </option>
                        <option value='<?= ChampionshipTypes::TRACK_MASTER ?>' <?= selected($this->championshipType, ChampionshipTypes::TRACK_MASTER, false) ?>>
                            <?= esc_html__('Track Master', 'sim-league-toolkit') ?>
                        </option>
                    </select>

                </td>
            </tr>
            <?php
        }

        public function theDescriptionField(): void {
            HtmlTagProvider::theAdminTextArea(esc_html__('Description', 'sim-league-toolkit'),
                    Championship::DESCRIPTION_FIELD_NAME,
                    $this->championship->getDescription(),
                    $this->getError(Championship::DESCRIPTION_FIELD_NAME),
                    50);
        }

        public function theEntryChangeLimitField(): void {
            $error = $this->getError(Championship::ENTRY_CHANGE_LIMIT_FIELD_NAME);

            $config = new HtmlTagProviderInputConfig(Championship::ENTRY_CHANGE_LIMIT_FIELD_NAME,
                    esc_html__('Entry Change Limit', 'sim-league-toolkit'),
                    $this->championship->getEntryChangeLimit(),
                    $error,
                    esc_html__('Entry Change Limit', 'sim-league-toolkit'),
                    'number');
            $config->min = 0;
            $config->max = 100;
            $config->step = 1;
            $config->tooltip = esc_html__('The number of times a driver can change their entry during the championship.', 'sim-league-toolkit');

            HtmlTagProvider::theAdminInputField($config);
        }

        public function theGameField(): void {
            HtmlTagProvider::theAdminTextDisplay(esc_html__('Game', 'sim-league-toolkit'), $this->championship->getGame());
        }

        public function theHiddenFields(): void {
            $this->theNonce();
        }

        public function theNameField(): void {
            HtmlTagProvider::theAdminTextInput(esc_html__('Name', 'sim-league-toolkit'),
                    Championship::NAME_FIELD_NAME,
                    $this->championship->getName(),
                    $this->getError(Championship::NAME_FIELD_NAME));
        }

        public function thePlatformField(): void {
            HtmlTagProvider::theAdminTextDisplay(esc_html__('Platform', 'sim-league-toolkit'), $this->championship->getPlatform());
        }

        public function theResultsToDiscardField(): void {
            $error = $this->getError(Championship::RESULTS_TO_DISCARD_FIELD_NAME);

            $config = new HtmlTagProviderInputConfig(Championship::RESULTS_TO_DISCARD_FIELD_NAME,
                    esc_html__('Results to Discard', 'sim-league-toolkit'),
                    $this->championship->getResultsToDiscard(),
                    $error,
                    esc_html__('Results To Discard', 'sim-league-toolkit'),
                    'number');
            $config->min = 0;
            $config->max = 100;
            $config->step = 1;
            $config->tooltip = esc_html__('The number of low scoring results that will be discarded to ensure a fair chance for all drivers to do well in the championship even if they miss an event or two.', 'sim-league-toolkit');

            HtmlTagProvider::theAdminInputField($config);

        }

        /**
         * @throws Exception
         */
        public function theRuleSetSelector(): void {
            $error = $this->getError(Championship::RULE_SET_ID_FIELD_NAME);
            ?>
            <tr>
                <th scope='row'>
                    <label class='form-label' for='<?= RuleSetSelectorComponent::FIELD_ID ?>'
                            <?= HtmlTagProvider::errorLabelClass($error) ?>
                           title='<?= $this->ruleSetSelectorComponent->getTooltip() ?>'>
                        <?= esc_html__('Rule Set', 'sim-league-toolkit') ?>
                    </label>
                </th>
                <td>
                    <?php
                        $this->ruleSetSelectorComponent->render();
                        HtmlTagProvider::theValidationError($error);
                    ?>
                </td>
            </tr>
            <?php
        }

        /**
         * @throws Exception
         */
        public function theScoringSetSelector(): void {
            $error = $this->getError(Championship::SCORING_SET_FIELD_NAME);
            ?>
            <tr>
                <th scope='row'>
                    <label class='form-label' for='<?= ScoringSetSelectorComponent::FIELD_ID ?>'
                            <?= HtmlTagProvider::errorLabelClass($error) ?>>
                        <?= esc_html__('Scoring Set', 'sim-league-toolkit') ?>
                    </label>
                </th>
                <td>
                    <?php
                        $this->scoringSetSelectorComponent->render();
                        HtmlTagProvider::theValidationError($error);
                    ?>
                </td>
            </tr>
            <?php
        }

        public function theStartDateField(): void {
            $error = $this->getError(Championship::START_DATE_FIELD_NAME);
            ?>
            <tr>
                <th scope='row'>
                    <label class='form-label'
                           for="<?= Championship::START_DATE_FIELD_NAME ?>"><?= esc_html__('Start Date', 'sim-league-toolkit') ?></label>
                </th>
                <td>
                    <input
                            type='text' class='date-picker-field' id='<?= Championship::START_DATE_FIELD_NAME ?>'
                            name="<?= Championship::START_DATE_FIELD_NAME ?>"
                            value="<?= $this->championship->getFormattedStartDate() ?>"
                            autocomplete='off'/>
                    <?php
                        HtmlTagProvider::theValidationError($error);
                    ?>
                </td>
            </tr>
            <?php
        }

        /**
         * @throws Exception
         */
        public function theTrackMasterTrackLayoutSelector(): void {

            if (!$this->championship->getIsTrackMasterChampionship() || !$this->game->getSupportsLayouts()) {
                return;
            }

            $error = $this->getError(Championship::TRACK_LAYOUT_ID_FIELD_NAME);
            ?>
            <tr>
                <th scope='row'>
                    <label class='form-label' for='<?= TrackLayoutSelectorComponent::FIELD_ID ?>'
                            <?= HtmlTagProvider::errorLabelClass($error) ?>
                           title='<?= $this->trackLayoutSelectorComponent->getTooltip() ?>'>
                        <?= esc_html__('Track Master Track Layout', 'sim-league-toolkit') ?>
                    </label>
                </th>
                <td>
                    <?php
                        $this->trackLayoutSelectorComponent->render();
                        HtmlTagProvider::theValidationError($error);
                    ?>
                </td>
            </tr>
            <?php
        }

        /**
         * @throws Exception
         */
        public function theTrackMasterTrackSelector(): void {
            if (!$this->championship->getIsTrackMasterChampionship()) {
                return;
            }

            $error = $this->getError(Championship::TRACK_ID_FIELD_NAME);
            ?>
            <tr>
                <th scope='row'>
                    <label class='form-label' for='<?= TrackSelectorComponent::FIELD_ID ?>'
                            <?= HtmlTagProvider::errorLabelClass($error) ?>
                           title='<?= $this->trackSelectorComponent->getTooltip() ?>'>
                        <?= esc_html__('Track Master Track', 'sim-league-toolkit') ?>
                    </label>
                </th>
                <td>
                    <?php
                        $this->trackSelectorComponent->render();
                        HtmlTagProvider::theValidationError($error);
                    ?>
                </td>
            </tr>
            <?php
        }

        private function initialiseState(): void {
            $this->championshipId = $this->getIdFromUrl();
            $this->championship = Championship::get($this->championshipId);

            $this->setChampionshipType();

            $this->game = Game::get($this->championship->getGameId());

            $this->ruleSetSelectorComponent = new RuleSetSelectorComponent(new SelectorComponentConfig(toolTip: esc_html__('The optional rule set that applies for the championship', 'sim-league-toolkit')));
            $this->scoringSetSelectorComponent = new ScoringSetSelectorComponent(new SelectorComponentConfig(toolTip: esc_html__('The scoring set that applies for the championship', 'sim-league-toolkit')));
            $this->trackSelectorComponent = new TrackSelectorComponent(new SelectorComponentConfig(false, true, toolTip: esc_html__('The track to be used for the entire track master championship', 'sim-league-toolkit')));
            $this->trackLayoutSelectorComponent = new TrackLayoutSelectorComponent(new SelectorComponentConfig(false, true, toolTip: esc_html__('The track layout to be used for the entire track master championship', 'sim-league-toolkit')));

            $this->ruleSetSelectorComponent->setValue($this->championship->getRuleSetId());
            $this->scoringSetSelectorComponent->setValue($this->championship->getScoringSetId());

            $this->trackSelectorComponent->setGameId($this->championship->getGameId());
            if($this->championship->getIsTrackMasterChampionship()) {
                $this->trackSelectorComponent->setValue($this->championship->getTrackMasterTrackId());
                $this->trackLayoutSelectorComponent->setTrackId($this->championship->getTrackMasterTrackId());
                $this->trackLayoutSelectorComponent->setValue($this->championship->getTrackMasterTrackLayoutId());
            }


        }

        private function processForm(): void {
            $validationResult = $this->championship->validate();
            if (!$validationResult->success) {
                $this->errors = $validationResult->validationErrors;

                return;
            }

            if ($this->championship->save()) {
                HtmlTagProvider::theSuccessMessage(esc_html__('The championship was saved successfully.',
                        'sim-league-toolkit'));
            } else {
                HtmlTagProvider::theWarningMessage(esc_html__('An unexpected error occurred while saving the championship. Please check the details and try again. If the problem persists please contact the package vendor.', 'sim-league-toolkit',
                        'sim-league-toolkit'));
            }
        }

        private function setChampionshipType(): void {
            if ($this->championship->getIsTrackMasterChampionship()) {
                $this->championshipType = ChampionshipTypes::TRACK_MASTER;

                return;
            }

            if (!$this->championship->getAllowEntryChange()) {
                $this->championshipType = ChampionshipTypes::STANDARD_NO_ENTRY_CHANGES;

                return;
            }

            $this->championshipType = ChampionshipTypes::STANDARD;
        }

        private function updateChampionshipFromPost(): void {
            $championshipName = $this->getSanitisedFieldFromPost(Championship::NAME_FIELD_NAME, '');
            $championshipDescription = $this->getSanitisedFieldFromPost(Championship::DESCRIPTION_FIELD_NAME, '');
            $this->championshipType = $this->getSanitisedFieldFromPost(Championship::TYPE_FIELD_NAME, ChampionshipTypes::STANDARD);
            $entryChangeLimit = $this->getSanitisedFieldFromPost(Championship::ENTRY_CHANGE_LIMIT_FIELD_NAME, 0);
            $isActive = $this->getSanitisedFieldFromPost(Championship::IS_ACTIVE_FIELD_NAME, false);
            $resultsToDiscard = $this->getSanitisedFieldFromPost(Championship::RESULTS_TO_DISCARD_FIELD_NAME, 0);
            $ruleSetId = $this->ruleSetSelectorComponent->getValue();
            $scoringSetId = $this->scoringSetSelectorComponent->getValue();
            $startDate = $this->getSanitisedFieldFromPost(Championship::START_DATE_FIELD_NAME);
            $trackMasterTrackLayoutId = $this->trackLayoutSelectorComponent->getValue();
            $trackMasterTrackId = $this->trackSelectorComponent->getValue();

            $this->championship->setDescription($championshipDescription);
            $this->championship->setEntryChangeLimit($entryChangeLimit);
            $this->championship->setIsActive($isActive);
            $this->championship->setName($championshipName);
            $this->championship->setRuleSetId((int)$ruleSetId);
            $this->championship->setTrackMasterTrackId($trackMasterTrackId);
            $this->championship->setResultsToDiscard($resultsToDiscard);
            $this->championship->setScoringSetId($scoringSetId);

            if (!empty($startDate)) {
                $this->championship->setStartDate(DateTime::createFromFormat(Constants::STANDARD_DATE_FORMAT, $startDate));
            }

            if ($ruleSetId == Constants::DEFAULT_ID) {
                $this->championship->setRuleSetId(null);
            } else {
                $this->championship->setRuleSetId($ruleSetId);
            }

            if ($this->championshipType !== ChampionshipTypes::TRACK_MASTER) {
                $this->championship->setTrackMasterTrackId(null);
                $this->championship->setTrackMasterTrackLayoutId(null);
                if ($this->championshipType === ChampionshipTypes::STANDARD_NO_ENTRY_CHANGES) {
                    $this->championship->setAllowEntryChange(false);
                    $this->championship->setEntryChangeLimit(0);
                } else {
                    $this->championship->setAllowEntryChange(true);
                    $this->championship->setEntryChangeLimit($entryChangeLimit);
                }
            } else {
                $this->championship->setTrackMasterTrackId($trackMasterTrackId);
                $this->championship->setTrackMasterTrackLayoutId($trackMasterTrackLayoutId);
                $this->championship->setAllowEntryChange(false);
                $this->championship->setEntryChangeLimit(0);
            }
        }

        private function updateChampionshipFromSelectedType(): void {
            $this->championshipType = $this->getSanitisedFieldFromPost(Championship::TYPE_FIELD_NAME, ChampionshipTypes::STANDARD);
            if ($this->championshipType == ChampionshipTypes::STANDARD) {
                $this->championship->setAllowEntryChange(true);
                $this->championship->setIsTrackMasterChampionship(false);

                return;
            }

            if ($this->championshipType == ChampionshipTypes::STANDARD_NO_ENTRY_CHANGES) {
                $this->championship->setAllowEntryChange(false);
                $this->championship->setIsTrackMasterChampionship(false);

                return;
            }

            if ($this->championshipType == ChampionshipTypes::TRACK_MASTER) {
                $this->championship->setAllowEntryChange(false);
                $this->championship->setIsTrackMasterChampionship(true);
            }
        }

        protected function handleGet(): void {
            $this->initialiseState();
        }

        protected function handlePost(): void {
            if (!$this->validateNonce()) {
                return;
            }
            $this->initialiseState();
            $this->updateChampionshipFromPost();
            $this->updateChampionshipFromSelectedType();

            if ($this->postContainsField(CommonFieldNames::SAVE_BUTTON)) {
                self::processForm();
            }
        }
    }