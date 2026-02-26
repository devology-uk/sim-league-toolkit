import {__} from '@wordpress/i18n';
import {useState} from '@wordpress/element';

import {Button} from 'primereact/button';
import {Column} from 'primereact/column';
import {ConfirmDialog, confirmDialog} from 'primereact/confirmdialog';
import {DataTable, DataTableRowReorderEvent} from 'primereact/datatable';
import {Dialog} from 'primereact/dialog';

import {Tooltip} from 'primereact/tooltip';

import {useGameConfig} from '../../../features/game';
import {DynamicSessionForm} from './DynamicSessionForm';
import {
    EventSession,
    EventSessionFormData,
    useCreateEventSession,
    useDeleteEventSession,
    useEventSessions,
    useReorderEventSessions,
    useUpdateEventSession,
} from '../../../features/eventSession';
import {SessionType, SessionTypeLabels} from '../../../enums/generated/SessionType';

interface EventSessionListProps {
    eventRefId: number;
    gameId: string;
}

export const EventSessionList = ({eventRefId, gameId}: EventSessionListProps) => {
    const {data: eventSessions = [], isLoading} = useEventSessions(eventRefId);
    const {mutateAsync: createEventSession, isPending: isCreating} = useCreateEventSession(eventRefId);
    const {mutateAsync: updateEventSession, isPending: isUpdating} = useUpdateEventSession(eventRefId);
    const {mutateAsync: deleteEventSession} = useDeleteEventSession(eventRefId);
    const {mutateAsync: reorderEventSessions} = useReorderEventSessions(eventRefId);

    const {data: gameConfig} = useGameConfig(gameId);

    const [dialogVisible, setDialogVisible] = useState(false);
    const [editingSession, setEditingSession] = useState<EventSession | null>(null);
    const [isQuickAdding, setIsQuickAdding] = useState(false);
    const saving = isCreating || isUpdating;

    const openCreateDialog = () => {
        setEditingSession(null);
        setDialogVisible(true);
    };

    const openEditDialog = (session: EventSession) => {
        setEditingSession(session);
        setDialogVisible(true);
    };

    const closeDialog = () => {
        setDialogVisible(false);
        setEditingSession(null);
    };

    const handleQuickAdd = async () => {
        const baseOrder = eventSessions.length;
        const quickSessions = [
            {name: SessionTypeLabels[SessionType.PRACTICE], sessionType: SessionType.PRACTICE, sortOrder: baseOrder},
            {name: SessionTypeLabels[SessionType.QUALIFYING], sessionType: SessionType.QUALIFYING, sortOrder: baseOrder + 1},
            {name: SessionTypeLabels[SessionType.RACE], sessionType: SessionType.RACE, sortOrder: baseOrder + 2},
        ];

        setIsQuickAdding(true);
        try {
            for (const session of quickSessions) {
                await createEventSession({eventRefId, gameId, attributes: {}, ...session});
            }
        } finally {
            setIsQuickAdding(false);
        }
    };

    const handleSubmit = async (data: EventSessionFormData) => {
        if (editingSession?.id) {
            await updateEventSession({id: editingSession.id, data});
        } else {
            await createEventSession(data);
        }
        closeDialog();
    };

    const handleDelete = (session: EventSession) => {
        confirmDialog({
                          message: __('Are you sure you want to delete this session?', 'sim-league-toolkit'),
                          header: __('Confirm Delete', 'sim-league-toolkit'),
                          icon: 'pi pi-exclamation-triangle',
                          acceptClassName: 'p-button-danger',
                          accept: () => {
                              if (session.id) {
                                  deleteEventSession(session.id).then(() => {});
                              }
                          },
                      });
    };

    const handleReorder = (e: DataTableRowReorderEvent<EventSession[]>) => {
        const reorderedSessions = e.value;
        const sessionIds = reorderedSessions
            .map((s) => s.id)
            .filter((id): id is number => id !== undefined);
        reorderEventSessions(sessionIds).then(() => {});
    };

    const tableHeader = () => (
        <div className='flex justify-content-between align-items-center'>
            <span>{__('Sessions', 'sim-league-toolkit')}</span>
            <div className='flex align-items-center gap-1'>
                <Tooltip target='.quick-add-sessions-info' position='top'/>
                <i
                    className='pi pi-info-circle quick-add-sessions-info'
                    data-pr-tooltip={__('Adds Practice, Qualifying and Race sessions with default values', 'sim-league-toolkit')}
                    style={{cursor: 'default'}}
                />
                <button
                    type='button'
                    className='p-panel-header-icon p-link'
                    onClick={handleQuickAdd}
                    disabled={isQuickAdding}
                    title={__('Quick Add Sessions', 'sim-league-toolkit')}
                >
                    <span className={isQuickAdding ? 'pi pi-spin pi-spinner' : 'pi pi-bolt'}></span>
                </button>
                <button
                    type='button'
                    className='p-panel-header-icon p-link'
                    onClick={openCreateDialog}
                    title={__('Add Session', 'sim-league-toolkit')}
                >
                    <span className='pi pi-plus'></span>
                </button>
            </div>
        </div>
    );

    const sessionTypeTemplate = (rowData: EventSession) => {
        return SessionTypeLabels[rowData.sessionType] ?? rowData.sessionType;
    };

    const actionsTemplate = (rowData: EventSession) => {
        return (
            <div className='flex gap-2'>
                <Button
                    icon='pi pi-pencil'
                    rounded
                    text
                    severity='info'
                    onClick={() => openEditDialog(rowData)}
                    tooltip={__('Edit', 'sim-league-toolkit')}
                />
                <Button
                    icon='pi pi-trash'
                    rounded
                    text
                    severity='danger'
                    onClick={() => handleDelete(rowData)}
                    tooltip={__('Delete', 'sim-league-toolkit')}
                />
            </div>
        );
    };

    return (
        <div className='event-session-list'>
            <ConfirmDialog/>

            <DataTable
                value={eventSessions}
                loading={isLoading}
                header={tableHeader}
                reorderableRows
                onRowReorder={handleReorder}
                emptyMessage={__('No sessions defined', 'sim-league-toolkit')}
            >
                <Column rowReorder style={{width: '3rem'}}/>
                <Column field='name' header={__('Name', 'sim-league-toolkit')}/>
                <Column
                    field='sessionType'
                    header={__('Type', 'sim-league-toolkit')}
                    body={sessionTypeTemplate}
                />
                <Column body={actionsTemplate} style={{width: '8rem'}}/>
            </DataTable>

            <Dialog
                visible={dialogVisible}
                onHide={closeDialog}
                header={
                    editingSession
                        ? __('Edit Session', 'sim-league-toolkit')
                        : __('Add Session', 'sim-league-toolkit')
                }
                style={{width: '500px'}}
                modal
            >
                <DynamicSessionForm
                    eventRefId={eventRefId}
                    gameId={gameId}
                    gameConfig={gameConfig ?? null}
                    initialData={editingSession ?? undefined}
                    onSubmit={handleSubmit}
                    onCancel={closeDialog}
                    loading={saving}
                />
            </Dialog>
        </div>
    );
};
