export interface MigrationRunResult {
    migratedCount: number;
    skippedCount: number;
    failedCount: number;
    messages: string[];
}
