<?php

  namespace SLTK\Migration;

  use Exception;
  use SLTK\Database\Repositories\MigrationRecordsRepository;
  use SLTK\Domain\ScoringSet;
  use SLTK\Domain\ScoringSetScore;
  use stdClass;

  class ScoringSetImporter implements MigrationImporter {
    private const string ENTITY_KEY = 'scoring-set';

    public function getEntityKey(): string {
      return self::ENTITY_KEY;
    }

    public function getLabel(): string {
      return __('Scoring sets', 'sim-league-toolkit');
    }

    public function run(): MigrationRunResult {
      $result = new MigrationRunResult();
      $legacyScoresBySetId = $this->buildLegacyScoresBySetId();

      foreach (AccltLegacyDatabase::getScoringSets() as $legacyScoringSet) {
        $this->migrateScoringSet($legacyScoringSet, $legacyScoresBySetId, $result);
      }

      return $result;
    }

    /**
     * @return array<int, stdClass[]>
     * @throws Exception
     */
    private function buildLegacyScoresBySetId(): array {
      $scoresBySetId = [];

      foreach (AccltLegacyDatabase::getScoringSetScores() as $score) {
        $scoresBySetId[(int)$score->scoringSetId][] = $score;
      }

      return $scoresBySetId;
    }

    private function migrateScoringSet(stdClass $legacyScoringSet, array $legacyScoresBySetId, MigrationRunResult $result): void {
      $legacyId = (int)$legacyScoringSet->id;

      try {
        if (MigrationRecordsRepository::isMigrated(self::ENTITY_KEY, $legacyId)) {
          $result->recordSkipped();
          return;
        }

        $scoringSet = new ScoringSet();
        $scoringSet->setName($legacyScoringSet->name);
        $scoringSet->save();

        MigrationRecordsRepository::recordMigration(self::ENTITY_KEY, $legacyId, $scoringSet->getId());

        foreach ($legacyScoresBySetId[$legacyId] ?? [] as $legacyScore) {
          $score = new ScoringSetScore();
          $score->setScoringSetId($scoringSet->getId());
          $score->setPosition((int)$legacyScore->position);
          $score->setPoints((int)$legacyScore->score);
          $scoringSet->saveScore($score);
        }

        $result->recordMigrated();
      } catch (Exception $e) {
        $result->recordFailed(sprintf(__('Scoring set %1$d (%2$s): %3$s', 'sim-league-toolkit'), $legacyId, $legacyScoringSet->name ?? '', $e->getMessage()));
      }
    }
  }
