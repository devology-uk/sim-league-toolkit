import {MigrationImporterStatus} from './MigrationImporterStatus';

export interface MigrationStatus {
    legacyThemeDetected: boolean;
    legacyThemeVersion: string | null;
    importers: MigrationImporterStatus[];
}
