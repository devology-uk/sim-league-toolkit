import {__} from '@wordpress/i18n';
import {useState, useCallback, useEffect} from '@wordpress/element';

import {ApiClient} from '../api/ApiClient';
import {CreateResponse} from '../types/CreateResponse';
import {ChampionshipClass} from '../types/ChampionshipClass';
import {ChampionshipClassFormData} from '../types/ChampionshipClassFormData';
import {
    championshipClassesGetEndpoint,
    championshipClassDeleteEndpoint,
    championshipClassPostEndpoint
} from '../api/endpoints/championshipApiEndpoints';

interface UseChampionshipClassesResult {
    championshipClasses: ChampionshipClass[];
    createChampionshipClass: (championshipId: number, data: ChampionshipClassFormData) => Promise<number | null>;
    deleteChampionshipClass: (id: number) => Promise<boolean>;
    isLoading: boolean;
    refresh: () => Promise<void>
}

export const useChampionshipClasses = (championshipId: number | null): UseChampionshipClassesResult => {
    const [championshipClasses, setChampionshipClasses] = useState<ChampionshipClass[]>([]);
    const [isLoading, setIsLoading] = useState(false);

    const refresh = useCallback(async () => {
        if (!championshipId) {
            setChampionshipClasses([]);
            return;
        }

        setIsLoading(true);
        const apiResponse = await ApiClient.get<ChampionshipClass[]>(
            championshipClassesGetEndpoint(championshipId)
        );

        if (apiResponse.success) {
            setChampionshipClasses(apiResponse.data ?? []);
        }

        setIsLoading(false);
    }, [championshipId]);

    const createChampionshipClass = useCallback(async (championshipId: number, data: ChampionshipClassFormData): Promise<number | null> => {
        const apiResponse = await ApiClient.post<CreateResponse>(
            championshipClassPostEndpoint(championshipId!),
            data
        );

        if (apiResponse.success && apiResponse.data) {
            ApiClient.showSuccess(__('Championship Class added successfully', 'sim-league-toolkit'));
            await refresh();
            return apiResponse.data.id;
        }

        return null;
    }, [championshipId, refresh]);

    const deleteChampionshipClass = useCallback(async (id: number): Promise<boolean> => {
        const apiResponse = await ApiClient.delete(championshipClassDeleteEndpoint(championshipId, id));

        if (apiResponse.success) {
            ApiClient.showSuccess(__('Championship Class deleted successfully', 'sim-league-toolkit'));
            await refresh();
        }

        return apiResponse.success;
    }, [refresh]);

    useEffect(() => {
        refresh().then(_ => {});
    }, [refresh]);

    return {
        championshipClasses,
        createChampionshipClass,
        deleteChampionshipClass,
        isLoading,
        refresh,
    };
};