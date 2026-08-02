import {__} from '@wordpress/i18n';
import {useMemo} from '@wordpress/element';

import {Button} from 'primereact/button';
import {Column} from 'primereact/column';
import {ConfirmDialog, confirmDialog} from 'primereact/confirmdialog';
import {DataTable} from 'primereact/datatable';
import {Message} from 'primereact/message';

import {BusyIndicator} from '../../components/BusyIndicator';
import {ProposedTrophy, TrophyPreviewResult} from '../../../features/trophy';
import {TrophyAwardTypeLabels} from '../../../enums/generated/TrophyAwardType';

interface AwardTrophiesPanelProps {
    trophiesAwarded: boolean;
    preview?: TrophyPreviewResult;
    isPreviewLoading: boolean;
    isAwarding: boolean;
    onAward: () => Promise<unknown>;
}

const awardOrder: Record<string, number> = {pole: 0, first: 1, second: 2, third: 3, fastestLap: 4};

export const AwardTrophiesPanel = ({trophiesAwarded, preview, isPreviewLoading, isAwarding, onAward}: AwardTrophiesPanelProps) => {
    const rows = useMemo(() => {
        const trophies = preview?.proposedTrophies ?? [];

        return [...trophies].sort((a, b) => {
            const sessionCompare = (a.sessionName ?? '').localeCompare(b.sessionName ?? '');
            if (sessionCompare !== 0) {
                return sessionCompare;
            }

            const classCompare = (a.className ?? '').localeCompare(b.className ?? '');
            if (classCompare !== 0) {
                return classCompare;
            }

            return (awardOrder[a.awardType] ?? 99) - (awardOrder[b.awardType] ?? 99);
        });
    }, [preview]);

    const showSessionColumn = new Set(rows.map((r) => r.sessionName)).size > 1;

    const handleAward = () => {
        confirmDialog({
                          message: trophiesAwarded
                              ? __('This will replace the trophies already awarded for this event with the set shown below. Continue?', 'sim-league-toolkit')
                              : __('Award the trophies shown below?', 'sim-league-toolkit'),
                          header: __('Confirm Award', 'sim-league-toolkit'),
                          icon: 'pi pi-trophy',
                          accept: () => {
                              onAward().then(() => {});
                          },
                      });
    };

    return (
        <div className='award-trophies-panel'>
            <ConfirmDialog/>
            <BusyIndicator isBusy={isPreviewLoading || isAwarding}/>

            {preview && !preview.canAward &&
                <Message severity='info' text={preview.reason ?? ''} style={{marginBottom: '1rem'}}/>}

            {preview && preview.canAward && <>
                {trophiesAwarded &&
                    <Message severity='warn'
                             text={__('Trophies have already been awarded for this event. Re-awarding will replace them with the set below.', 'sim-league-toolkit')}
                             style={{marginBottom: '1rem'}}/>}

                <DataTable value={rows} dataKey={(row: ProposedTrophy) => `${row.eventSessionId}-${row.awardType}-${row.eventClassId ?? 'overall'}`}
                           size='small' emptyMessage={__('No trophies to award.', 'sim-league-toolkit')}>
                    {showSessionColumn &&
                        <Column field='sessionName' header={__('Race', 'sim-league-toolkit')}/>}
                    <Column field='awardType' header={__('Award', 'sim-league-toolkit')}
                            body={(row: ProposedTrophy) => TrophyAwardTypeLabels[row.awardType] ?? row.awardType}/>
                    <Column field='memberName' header={__('Driver', 'sim-league-toolkit')}/>
                    <Column field='className' header={__('Class', 'sim-league-toolkit')}
                            body={(row: ProposedTrophy) => row.className ?? __('Overall', 'sim-league-toolkit')}/>
                </DataTable>

                <Button label={trophiesAwarded ? __('Re-award Trophies', 'sim-league-toolkit') : __('Award Trophies', 'sim-league-toolkit')}
                        icon='pi pi-trophy' disabled={isAwarding} onClick={handleAward} style={{marginTop: '1rem'}}/>
            </>}
        </div>
    );
};
