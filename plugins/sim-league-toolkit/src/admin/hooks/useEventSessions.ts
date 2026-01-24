import {useState, useEffect, useCallback} from '@wordpress/element';

import {ApiClient} from '../api/ApiClient';
import {CreateResponse} from '../types/CreateResponse';
import {EventSession} from '../types/EventSession';
import {
    eventSessionEndpoint,
    eventSessionsReorderEndpoint,
    eventSessionsByEventRefEndpoint,
    eventSessionRootEndpoint,
} from '../api/endpoints/eventSessionApiEndpoints';
import {EventSessionFormData} from '../types/EventSessionFormData';

interface UseEventSessionsResult {
    createEventSession: (data: EventSessionFormData) => Promise<number | null>;
    deleteEventSession: (id: number) => Promise<boolean>;
    eventSessions: EventSession[];
    isLoading: boolean;
    refresh: () => Promise<void>;
    reorderEventSessions: (sessionIds: number[]) => Promise<boolean>;
    updateEventSession: (id: number, data: EventSessionFormData) => Promise<boolean>;
}

export const useEventSessions = (eventRefId: number | null): UseEventSessionsResult => {
    const [eventSessions, setEventSessions] = useState<EventSession[]>([]);
    const [isLoading, setIsLoading] = useState(false);

    const refresh = useCallback(async () => {
        if (!eventRefId) {
            setEventSessions([]);
            return;
        }

        setIsLoading(true);

        const apiResponse = await ApiClient.get<EventSession[]>(eventSessionsByEventRefEndpoint(eventRefId));
        if (apiResponse.success) {
            setEventSessions(apiResponse.data);
        }
        setIsLoading(false);

    }, [eventRefId]);

    const createEventSession = useCallback(async (data: EventSessionFormData): Promise<number | null> => {
        const apiResponse = await ApiClient.post<CreateResponse>(eventSessionRootEndpoint(), data);
        await refresh();
        return apiResponse.data.id;
    }, [refresh]);

    const deleteEventSession = useCallback(async (id: number): Promise<boolean> => {
        const apiResponse = await ApiClient.delete(eventSessionEndpoint(id));
        await refresh();
        return apiResponse.success;
    }, [refresh]);

    const reorderEventSessions = useCallback(async (sessionIds: number[]): Promise<boolean> => {
        if (!eventRefId) {
            return false;
        }

        const apiResponse = await ApiClient.put(eventSessionsReorderEndpoint(eventRefId), sessionIds);
        await refresh();
        return apiResponse.success;
    }, [eventRefId, refresh]);

    const updateEventSession = useCallback(async (id: number, data: EventSessionFormData): Promise<boolean> => {
        const apiResponse = await ApiClient.put(eventSessionEndpoint(id), data);
        await refresh();
        return apiResponse.success;
    }, [refresh]);

    useEffect(() => {
        refresh().then(_ => {
        });
    }, [refresh]);

    return {
        createEventSession,
        deleteEventSession,
        eventSessions,
        isLoading,
        refresh,
        reorderEventSessions,
        updateEventSession,
    };
};