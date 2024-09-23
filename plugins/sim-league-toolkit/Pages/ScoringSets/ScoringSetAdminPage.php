<?php

namespace SLTK\Pages\ScoringSets;

use SLTK\Pages\AdminPage;

class ScoringSetAdminPage implements AdminPage {

    private ScoringSetAdminPageController $controller;

    public function __construct() {
        $this->controller = new ScoringSetAdminPageController();
    }

    public function render(): void {
        ?>
        <div class='wrap'>
            <h1><?= esc_html__('Scoring Set Details', 'sim-league-toolkit') ?></h1>
            <?php
            $this->controller->theInstructions();
            ?>
            <form method='post'>
                <?php
                $this->controller->theHiddenFields();
                ?>
                <table class='form-table'>
                    <?php
                    $this->controller->theNameField();
                    $this->controller->thePointsForFastestLapField();
                    $this->controller->thePointsForFinishingField();
                    $this->controller->thePointsForPoleField();
                    $this->controller->theDescriptionField();
                    ?>
                </table>
                <?php
                $this->controller->theSaveDetailsButton();
                ?>
            </form>
            <?php
            if ($this->controller->showScores()) { ?>
                <hr/>
                <h2><?= esc_html__('Scores', 'sim-league-toolkit') ?></h2>
                <table class='scoring-set-scores-table'>
                    <thead>
                    <tr>
                        <th><?= esc_html__('Position', 'sim-league-toolkit') ?></th>
                        <th><?= esc_html__('Points', 'sim-league-toolkit') ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $this->controller->theScores();
                    ?>
                    </tbody>
                </table>
                <?php
                $this->controller->theScoreSummary();
                ?>
                <hr/>
                <h3><?= esc_html__('New Score', 'sim-league-toolkit') ?></h3>
                <p><?= esc_html__('Complete the form below to add or edit a score.  Entering an existing position will change the points for that position.') ?></p>
                <form method='POST'>
                    <?php
                    $this->controller->theHiddenFields();
                    ?>
                    <table class='form-table'>
                        <?php
                        $this->controller->theNewScorePositionField();
                        $this->controller->theNewScorePointsField();
                        ?>
                    </table>
                    <?php
                    $this->controller->theSaveScoreButton();
                    ?>
                </form>
                <?php
                $this->controller->theFocusPositionFieldScript();
            }
            $this->controller->theBackButton();
            ?>
        </div>
        <?php
    }
}