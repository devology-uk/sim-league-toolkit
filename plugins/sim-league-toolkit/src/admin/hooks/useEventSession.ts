import { useState, useEffect, useCallback } from '@wordpress/element';
import apiFetch from '@wordpress/api-fetch';
import { dispatch } from '@wordpress/data';
import { store as noticesStore } from '@wordpress/notices';
import { __ } from '@wordpress/i18n';

import { EventSession } from '../types/EventSession';
import { CreateResponse } from '../types/CreateResponse';
import {
    eventSessionsByEventRefGetRoute,
    eventSessionPostRoute,
    eventSessionPutRoute,
    eventSessionDeleteRoute,
    eventSessionsReorderPutRoute,
} from '../api/routes/eventSessionApiRoutes';
import {EventSessionFormData} from '../types/EventSessionFormData';

interface UseEventSessionsResult {
    sessions: EventSession[];
    loading: boolean;
    refresh: () => Promise<void>;
    createSession: (data: EventSessionFormData) => Promise<number | null>;
    updateSession: (id: number, data: EventSessionFormData) => Promise<boolean>;
    deleteSession: (id: number) => Promise<boolean>;
    reorderSessions: (sessionIds: number[]) => Promise<boolean>;
}

export const useEventSessions = (eventRefId: number | null): UseEventSessionsResult => {
    const [sessions, setSessions] = useState<EventSession[]>([]);
    const [loading, setLoading] = useState(false);

    const showError = (message: string) => {
        dispatch(noticesStore).createErrorNotice(message, {
            isDismissible: true,
            type: 'snackbar',
        });
    };

    const showSuccess = (message: string) => {
        dispatch(noticesStore).createSuccessNotice(message, {
            isDismissible: true,
            type: 'snackbar',
        });
    };

    const refresh = useCallback(async () => {
        if (!eventRefId) {
            setSessions([]);
            return;
        }

        setLoading(true);

        try {
            const data = await apiFetch<EventSession[]>({
                                                            path: eventSessionsByEventRefGetRoute(eventRefId),
                                                            method: 'GET',
                                                        });
            setSessions(data);
        } catch (e) {
            showError(__('Failed to load sessions', 'sim-league-toolkit'));
        } finally {
            setLoading(false);
        }
    }, [eventRefId]);

    useEffect(() => {
        refresh();
    }, [refresh]);

    const createSession = useCallback(async (data: EventSessionFormData): Promise<number | null> => {
        try {
            const response = await apiFetch<CreateResponse>({
                                                                path: eventSessionPostRoute(),
                                                                method: 'POST',
                                                                data,
                                                            });

            showSuccess(__('Session created successfully', 'sim-league-toolkit'));
            await refresh();
            return response.id;
        } catch (e) {
            showError(__('Failed to create session', 'sim-league-toolkit'));
            return null;
        }
    }, [refresh]);

    const updateSession = useCallback(async (id: number, data: EventSessionFormData): Promise<boolean> => {
        try {
            await apiFetch({
                               path: eventSessionPutRoute(id),
                               method: 'PUT',
                               data,
                           });

            showSuccess(__('Session updated successfully', 'sim-league-toolkit'));
            await refresh();
            return true;
        } catch (e) {
            showError(__('Failed to update session', 'sim-league-toolkit'));
            return false;
        }
    }, [refresh]);

    const deleteSession = useCallback(async (id: number): Promise<boolean> => {
        try {
            await apiFetch({
                               path: eventSessionDeleteRoute(id),
                               method: 'DELETE',
                           });

            showSuccess(__('Session deleted successfully', 'sim-league-toolkit'));
            await refresh();
            return true;
        } catch (e) {
            showError(__('Failed to delete session', 'sim-league-toolkit'));
            return false;
        }
    }, [refresh]);

    const reorderSessions = useCallback(async (sessionIds: number[]): Promise<boolean> => {
        if (!eventRefId) return false;

        try {
            await apiFetch({
                               path: eventSessionsReorderPutRoute(eventRefId),
                               method: 'PUT',
                               data: { sessionIds },
                           });

            await refresh();
            return true;
        } catch (e) {
            showError(__('Failed to reorder sessions', 'sim-league-toolkit'));
            return false;
        }
    }, [eventRefId, refresh]);

    return {
        sessions,
        loading,
        refresh,
        createSession,
        updateSession,
        deleteSession,
        reorderSessions,
    };
};