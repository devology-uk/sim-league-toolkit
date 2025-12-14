<?php

    namespace SLTK\Pages\Championships\Tabs;

    use DateTime;
    use Exception;
    use SLTK\Components\GameSelectorComponent;
    use SLTK\Components\PlatformSelectorComponent;
    use SLTK\Core\AdminPageSlugs;
    use SLTK\Core\BannerImageProvider;
    use SLTK\Core\ChampionshipTypes;
    use SLTK\Core\Constants;
    use SLTK\Core\HtmlTagProvider;
    use SLTK\Core\QueryParamNames;
    use SLTK\Core\UrlBuilder;
    use SLTK\Core\Utility;
    use SLTK\Domain\Championship;
    use SLTK\Pages\ControllerBase;

    class ChampionshipDetailsTabController extends ControllerBase {

        private string $action = '';
        private ?Championship $championship;
        private int $championshipId = Constants::DEFAULT_ID;
        private string $championshipType = ChampionshipTypes::STANDARD;
        private GameSelectorComponent $gameSelectorComponent;
        private PlatformSelectorComponent $platformSelectorComponent;

        public function __construct() {
            $this->gameSelectorComponent = new GameSelectorComponent();
            $this->platformSelectorComponent = new PlatformSelectorComponent();
            parent::__construct();
        }

        public function theActiveField(): void {
            HtmlTagProvider::theAdminCheckboxInput(esc_html__('Active', 'sim-league-toolkit'), Championship::IS_ACTIVE_FIELD_NAME, $this->championship->getIsActive());
        }

        public function theDescriptionField(): void {
            HtmlTagProvider::theAdminTextArea(esc_html__('Description', 'sltk-league-toolkit'),
                    Championship::DESCRIPTION_FIELD_NAME,
                    $this->championship->getDescription(),
                    $this->getError(Championship::DESCRIPTION_FIELD_NAME),
                    50);
        }

        public function theGameSelector(): void {
            $error = $this->getError(GameSelectorComponent::FIELD_ID);
            ?>
            <tr>
                <th scope='row'>
                    <label for='<?= GameSelectorComponent::FIELD_ID ?>' <?= HtmlTagProvider::errorLabelClass($error) ?>>
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
        }

        public function theNameField(): void {
            HtmlTagProvider::theAdminTextInput(esc_html__('Name', 'sim-league-toolkit'),
                    Championship::NAME_FIELD_NAME,
                    $this->championship->getName(),
                    $this->getError(Championship::NAME_FIELD_NAME));
        }

        public function theNewChampionshipMessage(): void {
            if ($this->action !== Constants::ACTION_ADD) {
                return;
            }
            ?>
            <p>
                <?= esc_html__('To get started creating a new server select a game from the list.  Once the server is created you will not be able to change the game.', 'sim-league-toolkit') ?>
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
            $error = $this->getError(PlatformSelectorComponent::FIELD_ID);
            ?>
            <tr>
                <th scope='row'>
                    <label for='<?= PlatformSelectorComponent::FIELD_ID ?>'
                            <?= HtmlTagProvider::errorLabelClass($error) ?>>
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

        public function theStartDateField(): void { ?>
            <tr>
                <th scope='row'>
                    <label class='form-label' for="<?= Championship::START_DATE_FIELD_NAME ?>">Start Date</label>
                </th>
                <td>
                    <input
                            type='text' class='date-picker-field' name="<?= Championship::START_DATE_FIELD_NAME ?>"
                            value="<?= $this->championship->getFormattedStartDate() ?>"
                            autocomplete='off'/>
                </td>
            </tr>
            <?php
        }

        private function initialiseChampionshipFromPost(): void {
            $bannerImageUrl = $this->getSanitisedFieldFromPost(Championship::BANNER_IMAGE_URL_HIDDEN_FIELD_NAME);
            $championshipName = $this->getSanitisedFieldFromPost(Championship::NAME_FIELD_NAME);
            $championshipDescription = $this->getSanitisedFieldFromPost(Championship::DESCRIPTION_FIELD_NAME);
            $entryChangeLimit = $this->getSanitisedFieldFromPost(Championship::ENTRY_CHANGE_LIMIT_FIELD_NAME, 0);
            $gameId = $this->getSanitisedFieldFromPost(Championship::GAME_ID_FIELD_NAME, Constants::DEFAULT_ID);
            $isActive = $this->getSanitisedFieldFromPost(Championship::IS_ACTIVE_FIELD_NAME, false);
            $ruleSetId = $this->getSanitisedFieldFromPost(Championship::RULE_SET_ID_FIELD_NAME, -1);
            $startDate = $this->getSanitisedFieldFromPost(Championship::START_DATE_FIELD_NAME);
            $trackMasterTrackId = $this->getSanitisedFieldFromPost(Championship::TRACK_ID_FIELD_NAME, -1);
            $resultsToDiscard = $this->getSanitisedFieldFromPost(Championship::RESULTS_TO_DISCARD_FIELD_NAME, 0);
            $platformId = $this->getSanitisedFieldFromPost(Championship::PLATFORM_ID_FIELD_NAME, Constants::DEFAULT_ID);
            $this->championshipId = $this->getSanitisedFieldFromPost(Championship::CHAMPIONSHIP_ID_FIELD_NAME, Constants::DEFAULT_ID);

            if ($this->championshipId == Constants::DEFAULT_ID) {
                $this->championship = new Championship();
            } else {
                $this->championship = Championship::get($this->championshipId);
            }

            $this->championship->setDescription(Utility::transformWpEditorContent($championshipDescription));
            $this->championship->setEntryChangeLimit($entryChangeLimit);
            $this->championship->setIsActive($isActive);
            $this->championship->setName($championshipName);
            $this->championship->setRuleSetId((int)$ruleSetId);
            $this->championship->setTrackMasterTrackId($trackMasterTrackId);
            $this->championship->setResultsToDiscard($resultsToDiscard);
            $this->championship->setPlatformId($platformId);

            if (!empty($bannerImageUrl)) {
                $this->championship->setBannerImageUrl($bannerImageUrl);
            }

            if (!empty($startDate)) {
                $this->championship->setStartDate(DateTime::createFromFormat(Constants::STANDARD_DATE_FORMAT, $startDate));
            }
        }

        private function initialiseState(): void {
            $this->championshipId = $this->getIdFromUrl();
            $this->action = $this->getActionFromUrl();
        }

        private function processForm(): void {
            $bannerImageFile = $this->getFile(Championship::BANNER_IMAGE_FILE_FIELD_NAME);

            $validationResult = $this->championship->validate();
            if (!$validationResult->success) {
                $this->errors = $validationResult->validationErrors;

                return;
            }


            if (!empty($bannerImageFile['name'])) {
                $upload = wp_handle_upload($bannerImageFile, array('test_form' => false));
                $this->championship->setBannerImageUrl($upload['url']);
            } else {
                if ($this->championshipId === Constants::DEFAULT_ID) {
                    $this->championship->setBannerImageUrl(BannerImageProvider::getRandomBannerImageUrl());
                }
            }

            if ($this->championship->save()) {
                HtmlTagProvider::theSuccessMessage(esc_html__('The championship was saved successfully, please wait while it is fully loaded...',
                        'sim-league-toolkit'));
                $queryParams = [
                        QueryParamNames::ID => $this->championship->id,
                        QueryParamNames::ACTION => Constants::ACTION_EDIT
                ];

                $url = UrlBuilder::getAdminPageAbsoluteUrl(AdminPageSlugs::CHAMPIONSHIP, $queryParams);
                HtmlTagProvider::theRedirectScript($url, 1);
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

        private function updateChampionshipFromSelectedType(): void {
            $this->championshipType = $this->getSanitisedFieldFromPost(Championship::TYPE_FIELD_NAME);
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

            if ($this->championshipId !== Constants::DEFAULT_ID) {
                $this->championship = Championship::get($this->championshipId);
                $this->setChampionshipType();
                $this->gameSelectorComponent->setValue($this->championship->getGameId());
                $this->platformSelectorComponent->setGameId($this->championship->getGameId());

                return;
            }

            $this->championship = new Championship();
            $this->championshipType = ChampionshipTypes::STANDARD;
        }

        protected function handlePost(): void {
            if (!$this->validateNonce()) {
                return;
            }
            $this->initialiseState();
            $this->initialiseChampionshipFromPost();
            $this->updateChampionshipFromSelectedType();

            if ($this->postContainsField('submitFrm')) {
                self::processForm();
            }
        }
    }