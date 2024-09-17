<?php

  namespace SLTK\Pages\ScoringSets;

  use Exception;
  use SLTK\Core\AdminPageSlugs;
  use SLTK\Core\Constants;
  use SLTK\Core\HtmlTagProvider;
  use SLTK\Core\QueryParamNames;
  use SLTK\Core\UrlBuilder;
  use SLTK\Domain\ScoringSet;
  use SLTK\Domain\ScoringSetScore;
  use SLTK\Pages\ControllerBase;

  class ScoringSetAdminPageController extends ControllerBase {
    private const string SAVE_DETAILS_BUTTON_NAME = 'sltk_save_details';
    private const string SAVE_SCORE_BUTTON_NAME = 'sltk_save_score';

    private int $finalPosition = 0;
    private int $id = Constants::DEFAULT_ID;
    private ?ScoringSet $scoringSet;

    public function showScores(): bool {
      return $this->id !== Constants::DEFAULT_ID;
    }

    public function theBackButton(): void { ?>
      <p>
        <a href="<?= get_admin_url() . 'admin.php?page=' . AdminPageSlugs::SCORING_SETS ?>"
           class='button button-secondary'><?= esc_html__('Back to Scoring Sets', 'sim-league-toolkit') ?></a>
      </p>
      <?php
    }

    public function theDescriptionField(): void {
      HtmlTagProvider::theAdminTextArea(esc_html__('Description', 'sltk-league-toolkit'),
                                        ScoringSet::DESCRIPTION_FIELD_NAME,
                                        $this->scoringSet->getDescription(),
                                        $this->getError(ScoringSet::DESCRIPTION_FIELD_NAME),
                                        50);
    }

    public function theFocusPositionFieldScript(): void { ?>
      <script>
        document.getElementById('<?= ScoringSetScore::SCORE_POSITION_FIELD_NAME ?>').focus();
      </script>
      <?php
    }

    public function theHiddenFields(): void {
      $this->theNonce();
    }

    public function theInstructions(): void {
      if($this->id === Constants::DEFAULT_ID) {
        ?>
        <p>
          <?= esc_html__('Complete the form below and click save to create a new Scoring Set.  You will then be able to continue building the Scoring Set below.',
                         'sim-league-toolkit') ?>
        </p>
        <?php
      } else {
        ?>
        <p>
          <?= esc_html__('The Scoring Set is displayed below.  It is divided into 2 sections.  The details at the top can be changed and saved separately from the Scores, which you manage below.',
                         'sim-league-toolkit') ?>
        </p>
        <?php
      }
    }

    public function theNameField(): void {
      HtmlTagProvider::theAdminTextInput(esc_html__('Name', 'sim-league-toolkit'),
                                         ScoringSet::NAME_FIELD_NAME,
                                         $this->scoringSet->getName(),
                                         $this->getError(ScoringSet::NAME_FIELD_NAME));
    }

    public function theNewScorePointsField(): void {
      HtmlTagProvider::theAdminNumberInput(esc_html__('Points', 'sim-league-toolkit'),
                                           ScoringSetScore::SCORE_POINTS_FIELD_NAME,
                                           '',
                                           $this->getError(ScoringSetScore::SCORE_POINTS_FIELD_NAME),
                                           1);
    }

    public function theNewScorePositionField(): void {
      HtmlTagProvider::theAdminNumberInput(esc_html__('Position', 'sim-league-toolkit'),
                                           ScoringSetScore::SCORE_POSITION_FIELD_NAME,
                                           '',
                                           $this->getError(ScoringSetScore::SCORE_POSITION_FIELD_NAME),
                                           1);
    }

    public function thePointsForFastestLapField(): void {
      HtmlTagProvider::theAdminNumberInput(esc_html__('Points for Fastest Lap', 'sim-league-toolkit'),
                                           ScoringSet::POINTS_FOR_FASTEST_LAP_FIELD_NAME,
                                           $this->scoringSet->getPointsForFastestLap(),
                                           $this->getError(ScoringSet::POINTS_FOR_FASTEST_LAP_FIELD_NAME),
                                           0,
                                           100);
    }

    public function thePointsForFinishingField(): void {
      HtmlTagProvider::theAdminNumberInput(esc_html__('Points for Finishing', 'sim-league-toolkit'),
                                           ScoringSet::POINTS_FOR_FINISHING_FIELD_NAME,
                                           $this->scoringSet->getPointsForFinishing(),
                                           $this->getError(ScoringSet::POINTS_FOR_FINISHING_FIELD_NAME),
                                           0,
                                           100);
    }

    public function thePointsForPoleField(): void {
      HtmlTagProvider::theAdminNumberInput(esc_html__('Points for Pole', 'sim-league-toolkit'),
                                           ScoringSet::POINTS_FOR_POLE_FIELD_NAME,
                                           $this->scoringSet->getPointsForPole(),
                                           $this->getError(ScoringSet::POINTS_FOR_POLE_FIELD_NAME),
                                           0,
                                           100);
    }

    public function theSaveDetailsButton(): void { ?>
      <input type='submit' class='button button-primary' name='<?= self::SAVE_DETAILS_BUTTON_NAME ?>'
             value='<?= esc_html__('Save', 'sim-league-toolkit') ?>' />
      <?php
    }

    public function theSaveScoreButton(): void { ?>
      <input type='submit' class='button-primary' name='<?= self::SAVE_SCORE_BUTTON_NAME ?>'
             value='<?= esc_html__('Save', 'sim-league-toolkit') ?>' />
      <?php
    }

    public function theScoreSummary(): void { ?>
      <p><?= esc_html__('Positions after',
                        'sim-league-toolkit') ?> <?= $this->finalPosition ?> <?= esc_html__('will score',
                                                                                            'sim-league-toolkit') ?> <?= $this->scoringSet->getPointsForFinishing() ?> <?= esc_html__('point/s',
                                                                                                                                                                                      'sim-league-toolkit') ?></p>
      <?php
    }

    /**
     * @throws Exception
     */
    public function theScores(): void {
      $this->finalPosition = 0;
      $scores = $this->scoringSet->getScores();
      foreach($scores as $score) { ?>
        <tr>
          <td>
            <?= $score->getPosition() ?>
          </td>
          <td>
            <?= $score->getPoints() ?>
          </td>
        </tr>
        <?php
        $this->finalPosition = $score->getPosition();
      }
    }

    protected function handleGet(): void {
      $this->initialiseScoringSet();
    }

    protected function handlePost(): void {
      if(!$this->validateNonce()) {
        return;
      }

      $this->initialiseScoringSet();

      if($this->postContainsField(self::SAVE_DETAILS_BUTTON_NAME)) {
        $this->handleSaveDetails();
      } else {
        $this->handleAddScore();
      }
    }

    private function handleAddScore(): void {
      $score = new ScoringSetScore();
      $score->setScoringSetId($this->id);
      $score->setPosition($this->getSanitisedFieldFromPost(ScoringSetScore::SCORE_POSITION_FIELD_NAME, 0));
      $score->setPoints($this->getSanitisedFieldFromPost(ScoringSetScore::SCORE_POINTS_FIELD_NAME, 0));

      $validationResult = $score->validate();
      if(!$validationResult->success) {
        $this->errors = $validationResult->validationErrors;

        return;
      }

      if($this->scoringSet->saveScore($score)) {
        HtmlTagProvider::theSuccessMessage(esc_html__('The score was saved successfully.', 'sim-league-toolkit'));
      }
    }

    private function handleSaveDetails(): void {
      $this->scoringSet->setName($this->getSanitisedFieldFromPost(ScoringSet::NAME_FIELD_NAME, ''));
      $this->scoringSet->setDescription($this->getSanitisedFieldFromPost(ScoringSet::DESCRIPTION_FIELD_NAME, ''));
      $this->scoringSet->setPointsForFastestLap($this->getSanitisedFieldFromPost(ScoringSet::POINTS_FOR_FASTEST_LAP_FIELD_NAME,
                                                                                 0));
      $this->scoringSet->setPointsForFinishing($this->getSanitisedFieldFromPost(ScoringSet::POINTS_FOR_FINISHING_FIELD_NAME,
                                                                                0));
      $this->scoringSet->setPointsForPole($this->getSanitisedFieldFromPost(ScoringSet::POINTS_FOR_POLE_FIELD_NAME, 0));

      $validationResult = $this->scoringSet->validate();
      if(!$validationResult->success) {
        $this->errors = $validationResult->validationErrors;

        return;
      }

      if($this->scoringSet->save()) {
        HtmlTagProvider::theSuccessMessage(esc_html__('The scoring set was saved successfully, preparing scores please wait...',
                                                      'sim-league-toolkit'));
        $queryParams = [
          QueryParamNames::ID     => $this->scoringSet->id,
          QueryParamNames::ACTION => Constants::ACTION_EDIT
        ];

        $url = UrlBuilder::getAdminPageAbsoluteUrl(AdminPageSlugs::SCORING_SET, $queryParams);
        HtmlTagProvider::theRedirectScript($url, 2);
      }
    }

    private function initialiseScoringSet(): void {
      $action = $this->getActionFromUrl();
      if($action === Constants::ACTION_EDIT) {
        $this->id = $this->getIdFromUrl();
        $this->scoringSet = ScoringSet::get($this->id);
      } else {
        $this->scoringSet = new ScoringSet();
      }
    }

  }