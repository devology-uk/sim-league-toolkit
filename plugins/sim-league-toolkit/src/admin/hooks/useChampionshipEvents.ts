import {useState, useEffect, useCallback} from '@wordpress/element';

import {ApiClient} from '../api/ApiClient';
import {ChampionshipEvent} from '../types/ChampionshipEvent';
import {ChampionshipEventFormData} from '../types/ChampionshipEventFormData';
import {championshipEventsEndpoint, championshipEventEndpoint} from '../api/routes/championshipEventsApiRoutes';
import {CreateResponse} from '../types/CreateResponse';

interface UseChampionshipEventsResult {
    championshipEvents: ChampionshipEvent[];
    createChampionshipEvent: (data: ChampionshipEventFormData) => Promise<number | null>;
    deleteChampionshipEvent: (id: number) => Promise<boolean>;
    isLoading: boolean;
    refresh: () => Promise<void>;
    updateChampionshipEvent: (id: number, data: ChampionshipEventFormData) => Promise<boolean>;
}

export const useChampionshipEvents = (championshipId: number): UseChampionshipEventsResult => {
    const [championshipEvents, setChampionshipEvents] = useState<ChampionshipEvent[]>([]);
    const [isLoading, setIsLoading] = useState(true);

    const refresh = useCallback(async () => {
        if (!championshipId) {
            setChampionshipEvents([]);
            return;
        }

        setIsLoading(true);

        const apiResponse = await ApiClient.get<ChampionshipEvent[]>(championshipEventsEndpoint(championshipId));
        if (apiResponse.success) {
            setChampionshipEvents(apiResponse.data ?? []);
        }

        setIsLoading(false);
    }, [championshipId]);

    const createChampionshipEvent = useCallback(async (data: ChampionshipEventFormData): Promise<number | null> => {
        const apiResponse = await ApiClient.post<CreateResponse>(championshipEventsEndpoint(championshipId), data);
        await refresh();
        return apiResponse.data.id;
    }, [refresh]);

    const deleteChampionshipEvent = useCallback(async (id: number): Promise<boolean> => {
        const apiResponse = await ApiClient.delete(championshipEventEndpoint(id));
        await refresh();
        return apiResponse.success;
    }, [refresh]);

    const updateChampionshipEvent = useCallback(async (id: number, data: ChampionshipEventFormData): Promise<boolean> => {
        const apiResponse = await ApiClient.put(championshipEventsEndpoint(id), data);
        await refresh();
        return apiResponse.success;
    }, [refresh]);

    useEffect(() => {
        refresh().then(_ => {
        });
    }, [refresh]);

    return {
        championshipEvents,
        createChampionshipEvent,
        deleteChampionshipEvent,
        isLoading,
        refresh,
        updateChampionshipEvent
    };
};