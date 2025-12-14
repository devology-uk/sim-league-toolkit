<?php

    namespace SLTK\Pages\Championships;

    use DateTime;
    use Exception;
    use SLTK\Components\GameSelectorComponent;
    use SLTK\Components\PlatformSelectorComponent;
    use SLTK\Components\RuleSetSelectorComponent;
    use SLTK\Components\ScoringSetSelectorComponent;
    use SLTK\Components\SelectorComponentConfig;
    use SLTK\Components\TrackLayoutSelectorComponent;
    use SLTK\Components\TrackSelectorComponent;
    use SLTK\Core\AdminPageSlugs;
    use SLTK\Core\ChampionshipTypes;
    use SLTK\Core\CommonFieldNames;
    use SLTK\Core\Constants;
    use SLTK\Core\HtmlTagProvider;
    use SLTK\Core\HtmlTagProviderInputConfig;
    use SLTK\Core\QueryParamNames;
    use SLTK\Core\UrlBuilder;
    use SLTK\Domain\Championship;
    use SLTK\Domain\Game;
    use SLTK\Domain\Server;
    use SLTK\Pages\ControllerBase;

    class CreateChampionshipPageController extends ControllerBase {
        private Championship $championship;
        private string $championshipType = ChampionshipTypes::STANDARD;
        private Game $game;
        private GameSelectorComponent $gameSelectorComponent;
        private PlatformSelectorComponent $platformSelectorComponent;
        private RuleSetSelectorComponent $ruleSetSelectorComponent;
        private ScoringSetSelectorComponent $scoringSetSelectorComponent;
        private TrackLayoutSelectorComponent $trackLayoutSelectorComponent;
        private TrackSelectorComponent $trackSelectorComponent;

        public function theActiveField(): void {
            if ($this->championship->getGameId() === Constants::DEFAULT_ID) {
                return;
            }
            HtmlTagProvider::theAdminCheckboxInput(esc_html__('Active', 'sim-league-toolkit'), Championship::IS_ACTIVE_FIELD_NAME, $this->championship->getIsActive());
        }

        public function theBackButton(): void {
            $url = UrlBuilder::getAdminPageRelativeUrl(AdminPageSlugs::CHAMPIONSHIPS);
            ?>
            <a class='button button-secondary' href='<?= $url ?>'
               title='<?= esc_html__('Back To Championships', 'sim-league-toolkit') ?>'><?= esc_html__('Back To Championships', 'sim-league-toolkit') ?></a>
            <?php
        }

        public function theChampionshipTypeField(): void {
            if ($this->championship->getGameId() === Constants::DEFAULT_ID) {
                return;
            }
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
            if ($this->championship->getGameId() === Constants::DEFAULT_ID) {
                return;
            }
            HtmlTagProvider::theAdminTextArea(esc_html__('Description', 'sltk-league-toolkit'),
                    Championship::DESCRIPTION_FIELD_NAME,
                    $this->championship->getDescription(),
                    $this->getError(Championship::DESCRIPTION_FIELD_NAME),
                    50);
        }

        public function theEntryChangeLimitField(): void {
            if ($this->championship->getGameId() === Constants::DEFAULT_ID || $this->championshipType != ChampionshipTypes::STANDARD) {
                return;
            }

            $error = $this->getError(Championship::ENTRY_CHANGE_LIMIT_FIELD_NAME);

            $config = new HtmlTagProviderInputConfig(Championship::ENTRY_CHANGE_LIMIT_FIELD_NAME,
                    esc_html__('Entry Change Limit', 'sltk-league-toolkit'),
                    $this->championship->getEntryChangeLimit(),
                    $error,
                    esc_html__('Entry Change Limit', 'sltk-league-toolkit'),
                    'number');
            $config->min = 0;
            $config->max = 100;
            $config->step = 1;
            $config->tooltip = esc_html__('The number of times a driver can change their entry during the championship.', 'sltk-league-toolkit');

            HtmlTagProvider::theAdminInputField($config);
        }

        public function theGameSelector(): void {
            $error = $this->getError(Championship::GAME_ID_FIELD_NAME);
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
            HtmlTagProvider::theHiddenField(Server::GAME_ID_FIELD_NAME, $this->championship->getGameId());
        }

        public function theNameField(): void {
            if ($this->championship->getGameId() === Constants::DEFAULT_ID) {
                return;
            }

            HtmlTagProvider::theAdminTextInput(esc_html__('Name', 'sim-league-toolkit'),
                    Championship::NAME_FIELD_NAME,
                    $this->championship->getName() ?? '',
                    $this->getError(Championship::NAME_FIELD_NAME),
                    esc_html__('Unique championship name', 'sim-league-toolkit'));
        }

        public function theNewChampionshipMessage(): void {
            ?>
            <p>
                <?= esc_html__('To get started creating a new championship select a game from the list.  Once the championship is created you will not be able to change the game.', 'sim-league-toolkit') ?>
            </p>
            <?php
        }

        /**
         * @throws Exception
         */
        public function thePlatformSelector(): void {
            if ($this->championship->getGameId() === Constants::DEFAULT_ID) {
                return;
            }
            $error = $this->getError(Championship::PLATFORM_ID_FIELD_NAME);
            ?>
            <tr>
                <th scope='row'>
                    <label class='form-label' for='<?= PlatformSelectorComponent::FIELD_ID ?>'
                            <?= HtmlTagProvider::errorLabelClass($error) ?>
                           title='<?= $this->platformSelectorComponent->getTooltip() ?>'>
                        <?= esc_html__('Platform', 'sim-league-toolkit') ?>
                    </label>
                </th>
                <td>
                    <?php
                        $this->platformSelectorComponent->render();
                        HtmlTagProvider::theValidationError($error);
                    ?>
                </td>
            </tr>
            <?php
        }

        public function theResultsToDiscardField(): void {
            if ($this->championship->getGameId() === Constants::DEFAULT_ID) {
                return;
            }

            $error = $this->getError(Championship::RESULTS_TO_DISCARD_FIELD_NAME);

            $config = new HtmlTagProviderInputConfig(Championship::RESULTS_TO_DISCARD_FIELD_NAME,
                    esc_html__('Results to Discard', 'sltk-league-toolkit'),
                    $this->championship->getResultsToDiscard(),
                    $error,
                    esc_html__('Results To Discard', 'sltk-league-toolkit'),
                    'number');
            $config->min = 0;
            $config->max = 100;
            $config->step = 1;
            $config->tooltip = esc_html__('The number of low scoring results that will be discarded to ensure a fair chance for all drivers to do well in the championship even if they miss an event or two.', 'sltk-league-toolkit');

            HtmlTagProvider::theAdminInputField($config);

        }

        /**
         * @throws Exception
         */
        public function theRuleSetSelector(): void {
            if ($this->championship->getGameId() === Constants::DEFAULT_ID) {
                return;
            }
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

        public function theSaveButton(): void {
            if ($this->championship->getGameId() === Constants::DEFAULT_ID) {
                return;
            }
            ?>
            <input type='submit' class='button button-primary' id='<?= CommonFieldNames::SAVE_BUTTON ?>'
                   name='<?= CommonFieldNames::SAVE_BUTTON ?>' value='<?= esc_html__('Save', 'sim-league-toolkit') ?>'
                   title='<?= esc_html__('Save the new championship.', 'sim-league-toolkit') ?>'/>
            <?php
        }

        /**
         * @throws Exception
         */
        public function theScoringSetSelector(): void {
            if ($this->championship->getGameId() === Constants::DEFAULT_ID) {
                return;
            }
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
            if ($this->championship->getGameId() === Constants::DEFAULT_ID) {
                return;
            }

            $error = $this->getError(Championship::START_DATE_FIELD_NAME);
            ?>
            <tr>
                <th scope='row'>
                    <label class='form-label' for="<?= Championship::START_DATE_FIELD_NAME ?>"><?= esc_html__('Start Date', 'sltk-league-toolkit') ?></label>
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
            if ($this->championship->getGameId() === Constants::DEFAULT_ID
                    || $this->championshipType !== ChampionshipTypes::TRACK_MASTER
                    || $this->championship->getTrackMasterTrackId() === Constants::DEFAULT_ID
                    || !$this->game->getSupportsLayouts()) {
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
            if ($this->championship->getGameId() === Constants::DEFAULT_ID || $this->championshipType !== ChampionshipTypes::TRACK_MASTER) {
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

        private function initialiseChampionshipFromPost(): void {
            $championshipName = $this->getSanitisedFieldFromPost(Championship::NAME_FIELD_NAME, '');
            $championshipDescription = $this->getSanitisedFieldFromPost(Championship::DESCRIPTION_FIELD_NAME, '');
            $this->championshipType = $this->getSanitisedFieldFromPost(Championship::TYPE_FIELD_NAME, ChampionshipTypes::STANDARD);
            $entryChangeLimit = $this->getSanitisedFieldFromPost(Championship::ENTRY_CHANGE_LIMIT_FIELD_NAME, 0);
            $gameId = $this->gameSelectorComponent->getValue();
            $isActive = $this->getSanitisedFieldFromPost(Championship::IS_ACTIVE_FIELD_NAME, false);
            $platformId = $this->platformSelectorComponent->getValue();
            $resultsToDiscard = $this->getSanitisedFieldFromPost(Championship::RESULTS_TO_DISCARD_FIELD_NAME, 0);
            $ruleSetId = $this->ruleSetSelectorComponent->getValue();
            $scoringSetId = $this->scoringSetSelectorComponent->getValue();
            $startDate = $this->getSanitisedFieldFromPost(Championship::START_DATE_FIELD_NAME);
            $trackMasterTrackLayoutId = $this->trackLayoutSelectorComponent->getValue();
            $trackMasterTrackId = $this->trackSelectorComponent->getValue();

            $this->championship->setName($championshipName);
            $this->championship->setDescription($championshipDescription);
            $this->championship->setGameId($gameId);
            $this->championship->setIsActive($isActive);
            $this->championship->setPlatformId($platformId);
            $this->championship->setResultsToDiscard($resultsToDiscard);
            $this->championship->setScoringSetId($scoringSetId);
            if(!empty($startDate)) {
                $this->championship->setStartDate(DateTime::createFromFormat(Constants::STANDARD_DATE_FORMAT, $startDate));
            }

            if($ruleSetId == Constants::DEFAULT_ID) {
                $this->championship->setRuleSetId(null);
            } else {
                $this->championship->setRuleSetId($ruleSetId);
            }

            if($this->championshipType !== ChampionshipTypes::TRACK_MASTER) {
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

            $this->platformSelectorComponent->setGameId($gameId);
            $this->platformSelectorComponent->setValue($platformId);
            $this->trackSelectorComponent->setGameId($gameId);
            $this->trackSelectorComponent->setValue($trackMasterTrackId);

            if(isset($trackMasterTrackId)) {
                $this->trackLayoutSelectorComponent->setTrackId($trackMasterTrackId);
            }
            if(isset($trackMasterTrackLayoutId)) {
                $this->trackLayoutSelectorComponent->setValue($trackMasterTrackLayoutId);
            }

            if($gameId != Constants::DEFAULT_ID) {
                $this->game = Game::get($gameId);
            }
        }

        private function initialiseState(): void {
            $this->gameSelectorComponent = new GameSelectorComponent(new SelectorComponentConfig(true, true, esc_html__('The game used for the championship', 'sltk-league-toolkit')));
            $this->platformSelectorComponent = new PlatformSelectorComponent(new SelectorComponentConfig(toolTip: esc_html__('The platform used for the championship', 'sltk-league-toolkit')));
            $this->ruleSetSelectorComponent = new RuleSetSelectorComponent(new SelectorComponentConfig(toolTip: esc_html__('The optional rule set that applies for the championship', 'sltk-league-toolkit')));
            $this->scoringSetSelectorComponent = new ScoringSetSelectorComponent(new SelectorComponentConfig(toolTip: esc_html__('The scoring set that applies for the championship', 'sltk-league-toolkit')));
            $this->trackSelectorComponent = new TrackSelectorComponent(new SelectorComponentConfig(false, true, toolTip: esc_html__('The track to be used for the entire track master championship', 'sltk-league-toolkit')));
            $this->trackLayoutSelectorComponent = new TrackLayoutSelectorComponent(new SelectorComponentConfig(false, true, toolTip: esc_html__('The track layout to be used for the entire track master championship', 'sltk-league-toolkit')));

            $this->championship = new Championship();
        }

        private function processForm(): void {
            $validationResult = $this->championship->validate();
            var_dump($validationResult);
            if (!$validationResult->success) {
                $this->errors = $validationResult->validationErrors;
                return;
            }

            if($this->championship->save()) {
                $urlParameters = [
                    QueryParamNames::ID => $this->championship->id,
                    QueryParamNames::ACTION => Constants::ACTION_EDIT
                ];
                $url = UrlBuilder::getAdminPageAbsoluteUrl(AdminPageSlugs::CHAMPIONSHIP, $urlParameters);
                $this->theRedirectScript($url, 2);
            }
        }

        private function processGameSelection(): void {
            $gameId = (int)$this->gameSelectorComponent->getValue();
            if ($gameId === Constants::DEFAULT_ID) {
                HtmlTagProvider::theErrorMessage(__('You must select a game.', 'sim-league-toolkit'));

                return;
            }


            $this->platformSelectorComponent->setGameId($gameId);
            $this->trackSelectorComponent->setGameId($gameId);
            $this->championship->setGameId($gameId);
            $this->game = Game::get($gameId);
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

        private function updateTrackSelection(): void {
            if ($this->championshipType !== ChampionshipTypes::TRACK_MASTER) {
                return;
            }

            $this->championship->setTrackMasterTrackId($this->trackSelectorComponent->getValue());
            if (!$this->game->getSupportsLayouts()) {
                return;
            }

            $trackId = $this->championship->getTrackMasterTrackId();
            if(isset($trackId)) {
                $this->trackLayoutSelectorComponent->setTrackId($trackId);
            }
            $this->championship->setTrackMasterTrackLayoutId($this->trackLayoutSelectorComponent->getValue());
        }

        protected function handleGet(): void {
            $this->initialiseState();
        }

        protected function handlePost(): void {
            $this->initialiseState();
            $this->initialiseChampionshipFromPost();

            if (!$this->postContainsField(CommonFieldNames::SAVE_BUTTON)) {
                $this->processGameSelection();
                $this->updateChampionshipFromSelectedType();
                $this->updateTrackSelection();

                return;
            }

            $this->processForm();

        }
    }