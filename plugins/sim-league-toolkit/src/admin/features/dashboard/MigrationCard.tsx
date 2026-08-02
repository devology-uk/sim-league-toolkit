import {__, sprintf} from '@wordpress/i18n';
import {useState} from '@wordpress/element';

import {Button} from 'primereact/button';
import {Card} from 'primereact/card';
import {Message} from 'primereact/message';

import {BusyIndicator} from '../../components/BusyIndicator';
import {MigrationImporterRunResult, useMigrationStatus, useRunAllMigrationImporters} from '../../../features/migration';

export const MigrationCard = () => {
    const {data: status, isLoading} = useMigrationStatus();
    const runAll = useRunAllMigrationImporters();
    const [lastRunResults, setLastRunResults] = useState<MigrationImporterRunResult[]>([]);

    if (isLoading || !status?.legacyThemeDetected) {
        return null;
    }

    const handleRunAll = () => {
        runAll.mutate(undefined, {
            onSuccess: (results) => setLastRunResults(results),
        });
    };

    const resultFor = (entityKey: string) => lastRunResults.find((result) => result.entityKey === entityKey);

    return (
        <Card title={__('Legacy Data Migration', 'sim-league-toolkit')} style={{margin: '1rem 0', maxWidth: '600px'}}>
            <BusyIndicator isBusy={runAll.isPending}/>

            <Message severity='info'
                     text={__('The acc-league-tools theme is installed on this site. Migrating is safe to run repeatedly — already-migrated records are skipped, so you can keep both systems running side by side and pull across new data as it’s added.', 'sim-league-toolkit')}
                     style={{marginBottom: '1rem'}}/>

            <Button label={__('Migrate', 'sim-league-toolkit')} icon='pi pi-refresh'
                    disabled={runAll.isPending} onClick={handleRunAll} style={{marginBottom: '1rem'}}/>

            {status.importers.map((importer) => {
                const result = resultFor(importer.entityKey);

                return (
                    <div key={importer.entityKey} style={{marginBottom: '0.75rem'}}>
                        <div style={{display: 'flex', alignItems: 'center', justifyContent: 'space-between'}}>
                            <span>{importer.label}</span>
                            <span>{sprintf(__('%d migrated', 'sim-league-toolkit'), importer.migratedCount)}</span>
                        </div>

                        {result &&
                            <Message severity={result.failedCount > 0 ? 'warn' : 'success'}
                                     style={{marginTop: '0.25rem', width: '100%'}}
                                     text={sprintf(
                                         __('This run — migrated: %1$d, skipped: %2$d, failed: %3$d', 'sim-league-toolkit'),
                                         result.migratedCount,
                                         result.skippedCount,
                                         result.failedCount
                                     )}/>}
                    </div>
                );
            })}
        </Card>
    );
};
