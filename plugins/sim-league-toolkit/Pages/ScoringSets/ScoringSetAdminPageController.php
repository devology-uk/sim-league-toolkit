<?php

  namespace SLTK\Pages\ScoringSets;

  use SLTK\Core\Constants;
  use SLTK\Core\FieldNames;
  use SLTK\Core\HtmlTagProvider;
  use SLTK\Domain\ScoringSet;
  use SLTK\Pages\ControllerBase;

  class ScoringSetAdminPageController extends ControllerBase {
    private const string POINTS_FOR_FASTEST_LAP_FIELD_NAME = 'sltk_fastest_lap_points';
    private const string POINTS_FOR_FINISHING_FIELD_NAME = 'sltk_finish_points';
    private const string POINTS_FOR_POLE_FIELD_NAME = 'sltk_pole_points';

    private int $id = Constants::DEFAULT_ID;
    private ?ScoringSet $scoringSet;

    public function theHiddenFields(): void {
      $this->theNonce();
    }

    public function theInstructions(): void {
      if($this->id === Constants::DEFAULT_ID) {
        ?>
        <p>
          <?= esc_html__('Complete the form below and click save to create a new Scoring Set.  You will then be able to continue building the Scoring Set below.', 'sim-league-toolkit') ?>
        </p>
        <?php
      } else {
        ?>
        <p>
          <?= esc_html__('The Scoring Set is displayed below.  It is divided into 2 sections.  The details at the top can be changed and saved separately from the Scores, which you manage below.', 'sim-league-toolkit') ?>
        </p>
        <?php
      }
    }

    public function theNameField(): void {
      HtmlTagProvider::theAdminTextInput(esc_html__('Name', 'sim-league-toolkit'), FieldNames::NAME, $this->scoringSet->name ?? '');
    }

    public function thePointsForFastestLapField(): void {
      HtmlTagProvider::theAdminNumberInput(esc_html__('Points for Fastest Lap', 'sim-league-toolkit'), self::POINTS_FOR_FASTEST_LAP_FIELD_NAME, $this->scoringSet->pointsForFastestLap ?? 0, 0, 100);
    }

    public function thePointsForFinishingField(): void {
      HtmlTagProvider::theAdminNumberInput(esc_html__('Points for Finishing', 'sim-league-toolkit'), self::POINTS_FOR_FINISHING_FIELD_NAME, $this->scoringSet->pointsForFinishing ?? 0, 0, 100);
    }

    public function thePointsForPoleField(): void {
      HtmlTagProvider::theAdminNumberInput(esc_html__('Points for Pole', 'sim-league-toolkit'), self::POINTS_FOR_POLE_FIELD_NAME, $this->scoringSet->pointsForPole ?? 0, 0, 100);
    }

    protected function handleGet(): void {
      $action = $this->getActionFromUrl();
      if($action === Constants::ACTION_EDIT) {
        $this->id = $this->getIdFromUrl();
        $this->scoringSet = ScoringSet::get($this->id);
      } else {
        $this->scoringSet = new ScoringSet();
      }
    }

    protected function handlePost(): void {
      // TODO: Implement handlePost() method.
    }
  }