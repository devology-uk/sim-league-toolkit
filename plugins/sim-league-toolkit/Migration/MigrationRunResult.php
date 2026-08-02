<?php

  namespace SLTK\Migration;

  class MigrationRunResult {
    private int $failedCount = 0;
    private int $migratedCount = 0;

    /**
     * @var string[]
     */
    private array $messages = [];

    private int $skippedCount = 0;

    public function addWarning(string $message): void {
      $this->messages[] = $message;
    }

    public function recordFailed(string $message): void {
      $this->failedCount++;
      $this->messages[] = $message;
    }

    public function recordMigrated(): void {
      $this->migratedCount++;
    }

    public function recordSkipped(?string $message = null): void {
      $this->skippedCount++;

      if ($message !== null) {
        $this->messages[] = $message;
      }
    }

    public function toDto(): array {
      return [
        'migratedCount' => $this->migratedCount,
        'skippedCount' => $this->skippedCount,
        'failedCount' => $this->failedCount,
        'messages' => $this->messages,
      ];
    }
  }
