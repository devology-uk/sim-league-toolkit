import {__} from '@wordpress/i18n';
import {useState, useEffect, useCallback} from '@wordpress/element';

import {ApiClient} from '../api/ApiClient';
import {CreateResponse} from '../types/CreateResponse';
import {Championship} from '../types/Championship';
import {ChampionshipFormData} from '../types/ChampionshipFormData';
import {
    championshipsGetEndpoint,
    championshipPostEndpoint,
    championshipDeleteEndpoint,
    championshipPutEndpoint
} from '../api/endpoints/championshipApiEndpoints';

interface UseChampionshipsResult {
    championships: Championship[];
    createChampionship: (data: ChampionshipFormData) => Promise<number | null>;
    deleteChampionship: (id: number) => Promise<boolean>;
    findChampionship: (id: number) => Championship;
    isLoading: boolean;
    refresh: () => Promise<void>;
    updateChampionship: (id: number, data: ChampionshipFormData) => Promise<boolean>;
}

export const useChampionships = (): UseChampionshipsResult => {
    const [championships, setChampionships] = useState<Championship[]>([]);
    const [isLoading, setIsLoading] = useState(false);

    const refresh = useCallback(async () => {
        setIsLoading(true);

        const apiResponse = await ApiClient.get<Championship[]>(championshipsGetEndpoint());
        if (apiResponse.success) {
            setChampionships(apiResponse.data ?? []);
        }
        setIsLoading(false);

    }, []);

    const createChampionship = useCallback(async (data: ChampionshipFormData): Promise<number | null> => {
        const apiResponse = await ApiClient.post<CreateResponse>(championshipPostEndpoint(), data);
        if (apiResponse.success && apiResponse.data) {

            ApiClient.showSuccess(__('Championship created successfully', 'sim-league-toolkit'));
            await refresh();
            return apiResponse.data.id;
        }

        return null;

    }, [refresh]);

    const deleteChampionship = useCallback(async (id: number): Promise<boolean> => {
        const apiResponse = await ApiClient.delete(championshipDeleteEndpoint(id));
        if (apiResponse.success) {
            ApiClient.showSuccess(__('Championship deleted successfully', 'sim-league-toolkit'));
            await refresh();
        }
        return apiResponse.success;
    }, [refresh]);

    const updateChampionship = useCallback(async (id: number, data: ChampionshipFormData): Promise<boolean> => {
        const apiResponse = await ApiClient.put(championshipPutEndpoint(id), data);
        if (apiResponse.success) {
            ApiClient.showSuccess(__('Championship updated successfully', 'sim-league-toolkit'));
            await refresh();
        }
        return apiResponse.success;
    }, [refresh]);

    const findChampionship = (id: number) => championships.find(s => s.id === id);

    useEffect(() => {
        refresh().then(_ => {
        });
    }, [refresh]);

    return {
        championships,
        createChampionship,
        deleteChampionship,
        findChampionship,
        isLoading,
        refresh,
        updateChampionship,
    };

};

