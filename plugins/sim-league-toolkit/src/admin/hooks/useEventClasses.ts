import {__} from '@wordpress/i18n';
import {useState, useEffect, useCallback} from '@wordpress/element';

import {ApiClient} from '../api/ApiClient';
import {CreateResponse} from '../types/CreateResponse';
import {EventClass} from '../types/EventClass';
import {EventClassFormData} from '../types/EventClassFormData';
import {
    eventClassesGetEndpoint,
    eventClassPostEndpoint,
    eventClassDeleteEndpoint,
    eventClassPutEndpoint
} from '../api/endpoints/eventClassApiEndpoints';

interface UseEventClassesResult {
    createEventClass: (data: EventClassFormData) => Promise<number | null>;
    deleteEventClass: (id: number) => Promise<boolean>;
    eventClasses: EventClass[];
    findEventClass: (id: number) => EventClass;
    findEventClassesByGameId: (id: number) => EventClass[];
    isLoading: boolean;
    refresh: () => Promise<void>;
    updateEventClass: (id: number, data: EventClassFormData) => Promise<boolean>;
}

export const useEventClasses = (): UseEventClassesResult => {
    const [eventClasses, setEventClasses] = useState<EventClass[]>([]);
    const [isLoading, setIsLoading] = useState(false);

    const refresh = useCallback(async () => {
        setIsLoading(true);

        const apiResponse = await ApiClient.get<EventClass[]>(eventClassesGetEndpoint());
        if (apiResponse.success) {
            setEventClasses(apiResponse.data ?? []);
        }
        setIsLoading(false);

    }, []);

    const createEventClass = useCallback(async (data: EventClassFormData): Promise<number | null> => {
        const apiResponse = await ApiClient.post<CreateResponse>(eventClassPostEndpoint(), data);
        if (apiResponse.success && apiResponse.data) {

            ApiClient.showSuccess(__('Event Class created successfully', 'sim-league-toolkit'));
            await refresh();
            return apiResponse.data.id;
        }

        return null;

    }, [refresh]);

    const deleteEventClass = useCallback(async (id: number): Promise<boolean> => {
        const apiResponse = await ApiClient.delete(eventClassDeleteEndpoint(id));
        if (apiResponse.success) {
            ApiClient.showSuccess(__('Event Class deleted successfully', 'sim-league-toolkit'));
            await refresh();
        }
        return apiResponse.success;
    }, [refresh]);

    const updateEventClass = useCallback(async (id: number, data: EventClassFormData): Promise<boolean> => {
        const apiResponse = await ApiClient.put(eventClassPutEndpoint(id), data);
        if (apiResponse.success) {
            ApiClient.showSuccess(__('Event Class updated successfully', 'sim-league-toolkit'));
            await refresh();
        }
        return apiResponse.success;
    }, [refresh]);

    const findEventClass = (id: number): EventClass => eventClasses.find((ec) => ec.id === id);

    const findEventClassesByGameId = (gameId: number): EventClass[] => eventClasses.filter((ec) => ec.gameId === gameId);

    useEffect(() => {
        refresh().then(_ => {
        });
    }, [refresh]);

    return {
        createEventClass,
        deleteEventClass,
        eventClasses: eventClasses,
        findEventClass,
        findEventClassesByGameId,
        isLoading,
        refresh,
        updateEventClass,
    };

};

