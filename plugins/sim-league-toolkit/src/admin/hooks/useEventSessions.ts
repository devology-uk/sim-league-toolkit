import {useState, useEffect, useCallback} from '@wordpress/element';

import {ApiClient} from '../api/ApiClient';
import {CreateResponse} from '../types/CreateResponse';
import {EventSession} from '../types/EventSession';
import {
    eventSessionEndpoint,
    eventSessionsReorderEndpoint,
    eventSessionsByEventRefEndpoint,
    eventSessionRootEndpoint,
} from '../api/routes/eventSessionApiEndpoints';
import {EventSessionFormData} from '../types/EventSessionFormData';

interface UseEventSessionsResult {
    createSession: (data: EventSessionFormData) => Promise<number | null>;
    deleteSession: (id: number) => Promise<boolean>;
    isLoading: boolean;
    refresh: () => Promise<void>;
    reorderSessions: (sessionIds: number[]) => Promise<boolean>;
    sessions: EventSession[];
    updateSession: (id: number, data: EventSessionFormData) => Promise<boolean>;
}

export const useEventSessions = (eventRefId: number | null): UseEventSessionsResult => {
    const [sessions, setSessions] = useState<EventSession[]>([]);
    const [isLoading, setIsLoading] = useState(false);

    const refresh = useCallback(async () => {
        if (!eventRefId) {
            setSessions([]);
            return;
        }

        setIsLoading(true);

        const apiResponse = await ApiClient.get<EventSession[]>(eventSessionsByEventRefEndpoint(eventRefId));
        if (apiResponse.success) {
            setSessions(apiResponse.data);
        }
        setIsLoading(false);

    }, [eventRefId]);

    const createSession = useCallback(async (data: EventSessionFormData): Promise<number | null> => {
        const apiResponse = await ApiClient.post<CreateResponse>(eventSessionRootEndpoint(), data);
        await refresh();
        return apiResponse.data.id;
    }, [refresh]);

    const updateSession = useCallback(async (id: number, data: EventSessionFormData): Promise<boolean> => {
        const apiResponse = await ApiClient.put(eventSessionEndpoint(id), data);
        await refresh();
        return apiResponse.success;
    }, [refresh]);

    const deleteSession = useCallback(async (id: number): Promise<boolean> => {
        const apiResponse = await ApiClient.delete(eventSessionEndpoint(id));
        await refresh();
        return apiResponse.success;
    }, [refresh]);

    const reorderSessions = useCallback(async (sessionIds: number[]): Promise<boolean> => {
        if (!eventRefId) {
            return false;
        }

        const apiResponse = await ApiClient.put(eventSessionsReorderEndpoint(eventRefId), sessionIds);
        await refresh();
        return apiResponse.success;
    }, [eventRefId, refresh]);

    useEffect(() => {
        refresh();
    }, [refresh]);

    return {
        sessions,
        isLoading: isLoading,
        refresh,
        createSession,
        updateSession,
        deleteSession,
        reorderSessions,
    };
};