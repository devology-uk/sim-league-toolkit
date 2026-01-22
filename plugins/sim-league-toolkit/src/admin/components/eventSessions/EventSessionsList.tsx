import { useState } from '@wordpress/element';
import { __ } from '@wordpress/i18n';

import { DataTable, DataTableRowReorderEvent } from 'primereact/datatable';
import { Column } from 'primereact/column';
import { Button } from 'primereact/button';
import { Dialog } from 'primereact/dialog';
import { ConfirmDialog, confirmDialog } from 'primereact/confirmdialog';

import { useGameConfig } from '../../hooks/useGameConfig';
import { DynamicSessionForm } from './DynamicSessionForm';
import {useEventSessions} from '../../hooks/useEventSession';
import {EventSession} from '../../types/EventSession';
import {EventSessionFormData} from '../../types/EventSessionFormData';

interface Props {
    eventRefId: number;
    gameId: string;
}

export const EventSessionList = ({ eventRefId, gameId }: Props) => {
    const {
        sessions,
        loading,
        createSession,
        updateSession,
        deleteSession,
        reorderSessions,
    } = useEventSessions(eventRefId);

    const { config: gameConfig } = useGameConfig(gameId);

    const [dialogVisible, setDialogVisible] = useState(false);
    const [editingSession, setEditingSession] = useState<EventSession | null>(null);
    const [saving, setSaving] = useState(false);

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

    const handleSubmit = async (data: EventSessionFormData) => {
        setSaving(true);

        try {
            if (editingSession?.id) {
                await updateSession(editingSession.id, data);
            } else {
                await createSession(data);
            }
            closeDialog();
        } finally {
            setSaving(false);
        }
    };

    const handleDelete = (session: EventSession) => {
        confirmDialog({
                          message: __('Are you sure you want to delete this session?', 'sim-league-toolkit'),
                          header: __('Confirm Delete', 'sim-league-toolkit'),
                          icon: 'pi pi-exclamation-triangle',
                          acceptClassName: 'p-button-danger',
                          accept: async () => {
                              if (session.id) {
                                  await deleteSession(session.id);
                              }
                          },
                      });
    };

    const handleReorder = async (e: DataTableRowReorderEvent<EventSession[]>) => {
        const reorderedSessions = e.value;
        const sessionIds = reorderedSessions
            .map((s) => s.id)
            .filter((id): id is number => id !== undefined);
        await reorderSessions(sessionIds);
    };

    const sessionTypeTemplate = (rowData: EventSession) => {
        return SessionTypeLabels[rowData.sessionType] ?? rowData.sessionType;
    };

    const durationTemplate = (rowData: EventSession) => {
        return `${rowData.duration} ${__('min', 'sim-league-toolkit')}`;
    };

    const actionsTemplate = (rowData: EventSession) => {
        return (
            <div className="flex gap-2">
                <Button
                    icon="pi pi-pencil"
                    rounded
                    text
                    severity="info"
                    onClick={() => openEditDialog(rowData)}
                    tooltip={__('Edit', 'sim-league-toolkit')}
                />
                <Button
                    icon="pi pi-trash"
                    rounded
                    text
                    severity="danger"
                    onClick={() => handleDelete(rowData)}
                    tooltip={__('Delete', 'sim-league-toolkit')}
                />
            </div>
        );
    };

    return (
        <div className="event-session-list">
            <ConfirmDialog />

            <div className="flex justify-content-between align-items-center mb-3">
                <h3>{__('Sessions', 'sim-league-toolkit')}</h3>
                <Button
                    label={__('Add Session', 'sim-league-toolkit')}
                    icon="pi pi-plus"
                    onClick={openCreateDialog}
                />
            </div>

            <DataTable
                value={sessions}
                loading={loading}
                reorderableRows
                onRowReorder={handleReorder}
                emptyMessage={__('No sessions defined', 'sim-league-toolkit')}
            >
                <Column rowReorder style={{ width: '3rem' }} />
                <Column field="name" header={__('Name', 'sim-league-toolkit')} />
                <Column
                    field="sessionType"
                    header={__('Type', 'sim-league-toolkit')}
                    body={sessionTypeTemplate}
                />
                <Column field="startTime" header={__('Start Time', 'sim-league-toolkit')} />
                <Column
                    field="duration"
                    header={__('Duration', 'sim-league-toolkit')}
                    body={durationTemplate}
                />
                <Column body={actionsTemplate} style={{ width: '8rem' }} />
            </DataTable>

            <Dialog
                visible={dialogVisible}
                onHide={closeDialog}
                header={
                    editingSession
                        ? __('Edit Session', 'sim-league-toolkit')
                        : __('Add Session', 'sim-league-toolkit')
                }
                style={{ width: '500px' }}
                modal
            >
                <DynamicSessionForm
                    eventRefId={eventRefId}
                    gameId={gameId}
                    gameConfig={gameConfig}
                    initialData={editingSession ?? undefined}
                    onSubmit={handleSubmit}
                    onCancel={closeDialog}
                    loading={saving}
                />
            </Dialog>
        </div>
    );
};