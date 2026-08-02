import {__} from '@wordpress/i18n';
import {useMemo, useState} from '@wordpress/element';
import {ReactNode} from 'react';

import {Button} from 'primereact/button';
import {Column, ColumnEditorOptions} from 'primereact/column';
import {ConfirmDialog, confirmDialog} from 'primereact/confirmdialog';
import {DataTable, DataTableRowEditCompleteEvent} from 'primereact/datatable';
import {Dropdown} from 'primereact/dropdown';
import {InputNumber} from 'primereact/inputnumber';
import {InputText} from 'primereact/inputtext';

import {BusyIndicator} from '../../components/BusyIndicator';
import {formatLapTimeMs, parseLapTimeMs} from '../../utils/lapTime';
import {ResultStatus, ResultStatusLabels, ResultStatusOptions} from '../../../enums/generated/ResultStatus';

export interface SessionResultEntrant {
    entryId: number;
    memberName: string;
    raceNumber: number;
    className: string | null;
}

// Editable fields only - points is assigned server-side from the scoring set when a result is
// saved, never entered directly, so it's not part of the submittable shape.
export interface SessionResultFormValues {
    position: number | null;
    totalTimeMs: number | null;
    fastestLapMs: number | null;
    sector1TimeMs: number | null;
    sector2TimeMs: number | null;
    sector3TimeMs: number | null;
    lapsCompleted: number;
    status: ResultStatus;
}

export interface SessionResultData extends SessionResultFormValues {
    id?: number;
    entryId: number;
    points: number | null;
}

interface SessionResultRow extends SessionResultFormValues {
    entryId: number;
    resultId?: number;
    memberName: string;
    raceNumber: number;
    className: string | null;
    points: number | null;
}

interface SessionResultsGridProps {
    entrants: SessionResultEntrant[];
    results: SessionResultData[];
    isLoading: boolean;
    onSaveResult: (entryId: number, resultId: number | undefined, data: SessionResultFormValues) => Promise<void>;
    onDeleteResult: (resultId: number) => Promise<void>;
    renderPenaltyPanel: (resultId: number) => ReactNode;
}

const defaultFormValues: SessionResultFormValues = {
    position: null,
    totalTimeMs: null,
    fastestLapMs: null,
    sector1TimeMs: null,
    sector2TimeMs: null,
    sector3TimeMs: null,
    lapsCompleted: 0,
    status: ResultStatus.FINISHED,
};

export const SessionResultsGrid = ({
                                        entrants,
                                        results,
                                        isLoading,
                                        onSaveResult,
                                        onDeleteResult,
                                        renderPenaltyPanel,
                                    }: SessionResultsGridProps) => {
    const [editingRows, setEditingRows] = useState<Record<number, boolean>>({});
    const [expandedRows, setExpandedRows] = useState<Record<number, boolean>>({});

    const distinctClasses = useMemo(
        () => Array.from(new Set(entrants.map((e) => e.className).filter((c): c is string => !!c))),
        [entrants]
    );
    const [groupByClass, setGroupByClass] = useState(distinctClasses.length > 1);

    const rows = useMemo<SessionResultRow[]>(() => {
        const merged = entrants.map((entrant): SessionResultRow => {
            const existing = results.find((r) => r.entryId === entrant.entryId);
            return {
                ...entrant,
                resultId: existing?.id,
                points: existing?.points ?? null,
                ...(existing ? {
                    position: existing.position,
                    totalTimeMs: existing.totalTimeMs,
                    fastestLapMs: existing.fastestLapMs,
                    sector1TimeMs: existing.sector1TimeMs,
                    sector2TimeMs: existing.sector2TimeMs,
                    sector3TimeMs: existing.sector3TimeMs,
                    lapsCompleted: existing.lapsCompleted,
                    status: existing.status,
                } : defaultFormValues),
            };
        });

        const positionOf = (row: SessionResultRow) => row.position ?? Number.MAX_SAFE_INTEGER;

        if (groupByClass) {
            return merged.sort((a, b) => {
                const classCompare = (a.className ?? '').localeCompare(b.className ?? '');
                return classCompare !== 0 ? classCompare : positionOf(a) - positionOf(b);
            });
        }

        return merged.sort((a, b) => positionOf(a) - positionOf(b));
    }, [entrants, results, groupByClass]);

    const onRowEditComplete = async (e: DataTableRowEditCompleteEvent) => {
        const row = e.newData as SessionResultRow;

        await onSaveResult(row.entryId, row.resultId, {
            position: row.position,
            totalTimeMs: row.totalTimeMs,
            fastestLapMs: row.fastestLapMs,
            sector1TimeMs: row.sector1TimeMs,
            sector2TimeMs: row.sector2TimeMs,
            sector3TimeMs: row.sector3TimeMs,
            lapsCompleted: row.lapsCompleted,
            status: row.status,
        });
    };

    const handleDelete = (row: SessionResultRow) => {
        if (!row.resultId) {
            return;
        }

        confirmDialog({
                          message: __('Are you sure you want to delete this result?', 'sim-league-toolkit'),
                          header: __('Confirm Delete', 'sim-league-toolkit'),
                          icon: 'pi pi-exclamation-triangle',
                          acceptClassName: 'p-button-danger',
                          accept: async () => {
                              await onDeleteResult(row.resultId as number);
                          },
                      });
    };

    const positionEditor = (options: ColumnEditorOptions) => (
        <InputNumber value={options.value as number | null} min={1} useGrouping={false}
                     style={{width: '3rem'}} inputStyle={{width: '3rem'}}
                     onChange={(e) => options.editorCallback?.(e.value ?? null)}/>
    );

    const timeEditor = (options: ColumnEditorOptions) => (
        <InputText value={formatLapTimeMs(options.value as number | null)}
                   placeholder='m:ss.mmm' style={{width: '5rem'}}
                   onChange={(e) => options.editorCallback?.(parseLapTimeMs(e.target.value))}/>
    );

    const sectorTimeEditor = (options: ColumnEditorOptions) => (
        <InputText value={formatLapTimeMs(options.value as number | null)}
                   placeholder='ss.mmm' style={{width: '3.5rem'}}
                   onChange={(e) => options.editorCallback?.(parseLapTimeMs(e.target.value))}/>
    );

    const lapsEditor = (options: ColumnEditorOptions) => (
        <InputNumber value={options.value as number} min={0} useGrouping={false}
                     style={{width: '3rem'}} inputStyle={{width: '3rem'}}
                     onChange={(e) => options.editorCallback?.(e.value ?? 0)}/>
    );

    const statusEditor = (options: ColumnEditorOptions) => (
        <Dropdown value={options.value} options={[...ResultStatusOptions]} optionLabel='label' optionValue='value'
                  style={{width: '4.5rem'}}
                  onChange={(e) => options.editorCallback?.(e.value)}/>
    );

    const entrantTemplate = (row: SessionResultRow) => (
        <span>{row.memberName}{row.raceNumber > 0 ? ` (#${row.raceNumber})` : ''}</span>
    );

    const timeTemplate = (value: number | null) => formatLapTimeMs(value) || '-';

    const statusTemplate = (row: SessionResultRow) => ResultStatusLabels[row.status] ?? row.status;

    const actionsTemplate = (row: SessionResultRow) => (
        <Button icon='pi pi-trash' rounded text severity='danger' disabled={!row.resultId}
                onClick={() => handleDelete(row)}
                tooltip={__('Delete result', 'sim-league-toolkit')}/>
    );

    const rowExpansionTemplate = (row: SessionResultRow) => {
        if (!row.resultId) {
            return (
                <p style={{padding: '0.5rem 1rem'}}>
                    {__('Save this result before recording penalties.', 'sim-league-toolkit')}
                </p>
            );
        }

        return <div style={{padding: '0.5rem 1rem'}}>{renderPenaltyPanel(row.resultId)}</div>;
    };

    const tableHeader = () => (
        <div className='flex justify-content-between align-items-center'>
            <span>{__('Results', 'sim-league-toolkit')}</span>
            {distinctClasses.length > 1 && (
                <Button label={groupByClass ? __('Show Overall', 'sim-league-toolkit') : __('Group by Class', 'sim-league-toolkit')}
                        text size='small' onClick={() => setGroupByClass(!groupByClass)}/>
            )}
        </div>
    );

    return (
        <div className='session-results-grid'>
            <ConfirmDialog/>
            <BusyIndicator isBusy={isLoading}/>

            <DataTable value={rows} dataKey='entryId' header={tableHeader}
                       editMode='row' editingRows={editingRows}
                       onRowEditChange={(e) => setEditingRows(e.data as Record<number, boolean>)}
                       onRowEditComplete={onRowEditComplete}
                       expandedRows={expandedRows}
                       onRowToggle={(e) => setExpandedRows(e.data as Record<number, boolean>)}
                       rowExpansionTemplate={rowExpansionTemplate}
                       rowGroupMode={groupByClass ? 'subheader' : undefined}
                       groupRowsBy={groupByClass ? 'className' : undefined}
                       rowGroupHeaderTemplate={(row: SessionResultRow) => <strong>{row.className}</strong>}
                       emptyMessage={__('No entrants to show results for.', 'sim-league-toolkit')}
                       size='small' tableStyle={{tableLayout: 'fixed'}}>
                <Column expander style={{width: '2.5rem'}}/>
                <Column field='position' header={__('Pos', 'sim-league-toolkit')} editor={positionEditor}
                        body={(row: SessionResultRow) => row.position ?? '-'} style={{width: '3.3rem'}}/>
                <Column field='memberName' header={__('Driver', 'sim-league-toolkit')} body={entrantTemplate}
                        style={{width: '9rem'}}/>
                <Column field='totalTimeMs' header={__('Total Time', 'sim-league-toolkit')} editor={timeEditor}
                        body={(row: SessionResultRow) => timeTemplate(row.totalTimeMs)} style={{width: '5.3rem'}}/>
                <Column field='fastestLapMs' header={__('Fastest Lap', 'sim-league-toolkit')} editor={timeEditor}
                        body={(row: SessionResultRow) => timeTemplate(row.fastestLapMs)} style={{width: '5.3rem'}}/>
                <Column field='sector1TimeMs' header={__('S1', 'sim-league-toolkit')} editor={sectorTimeEditor}
                        body={(row: SessionResultRow) => timeTemplate(row.sector1TimeMs)} style={{width: '4rem'}}/>
                <Column field='sector2TimeMs' header={__('S2', 'sim-league-toolkit')} editor={sectorTimeEditor}
                        body={(row: SessionResultRow) => timeTemplate(row.sector2TimeMs)} style={{width: '4rem'}}/>
                <Column field='sector3TimeMs' header={__('S3', 'sim-league-toolkit')} editor={sectorTimeEditor}
                        body={(row: SessionResultRow) => timeTemplate(row.sector3TimeMs)} style={{width: '4rem'}}/>
                <Column field='lapsCompleted' header={__('Laps', 'sim-league-toolkit')} editor={lapsEditor}
                        style={{width: '3.3rem'}}/>
                <Column field='status' header={__('Status', 'sim-league-toolkit')} editor={statusEditor}
                        body={statusTemplate} style={{width: '5rem'}}/>
                <Column field='points' header={__('Points', 'sim-league-toolkit')}
                        body={(row: SessionResultRow) => row.points ?? '-'} style={{width: '3.3rem'}}/>
                <Column rowEditor style={{width: '5rem'}}/>
                <Column body={actionsTemplate} style={{width: '3rem'}}/>
            </DataTable>
        </div>
    );
};
