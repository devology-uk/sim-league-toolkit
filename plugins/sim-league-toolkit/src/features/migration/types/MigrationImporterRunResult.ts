import {MigrationRunResult} from './MigrationRunResult';

export interface MigrationImporterRunResult extends MigrationRunResult {
    entityKey: string;
    label: string;
}
