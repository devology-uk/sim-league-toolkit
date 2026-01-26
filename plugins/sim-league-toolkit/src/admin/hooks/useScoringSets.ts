import {__} from '@wordpress/i18n';
import {useState, useEffect, useCallback} from '@wordpress/element';

import {ApiClient} from '../api/ApiClient';
import {CreateResponse} from '../types/CreateResponse';
import {ScoringSet} from '../types/ScoringSet';
import {ScoringSetFormData} from '../types/ScoringSetFormData';
import {
    scoringSetsGetEndpoint,
    scoringSetPostEndpoint,
    scoringSetDeleteEndpoint, scoringSetPutEndpoint
} from '../api/endpoints/scoringSetApiEndpoints';

interface UseScoringSetsResult {
    createScoringSet: (data: ScoringSetFormData) => Promise<number | null>;
    deleteScoringSet: (id: number) => Promise<boolean>;
    findScoringSet: (id: number) => ScoringSet;
    isLoading: boolean;
    refresh: () => Promise<void>;
    scoringSets: ScoringSet[];
    updateScoringSet: (id: number, data: ScoringSetFormData) => Promise<boolean>;
}

export const useScoringSets = (): UseScoringSetsResult => {
    const [scoringSets, setScoringSets] = useState<ScoringSet[]>([]);
    const [isLoading, setIsLoading] = useState(false);

    const refresh = useCallback(async () => {
        setIsLoading(true);

        const apiResponse = await ApiClient.get<ScoringSet[]>(scoringSetsGetEndpoint());
        if (apiResponse.success) {
            setScoringSets(apiResponse.data ?? []);
        }
        setIsLoading(false);

    }, []);

    const createScoringSet = useCallback(async (data: ScoringSetFormData): Promise<number | null> => {
        const apiResponse = await ApiClient.post<CreateResponse>(scoringSetPostEndpoint(), data);
        if (apiResponse.success && apiResponse.data) {

            ApiClient.showSuccess(__('Scoring Set created successfully', 'sim-league-toolkit'));
            await refresh();
            return apiResponse.data.id;
        }

        return null;

    }, [refresh]);

    const deleteScoringSet = useCallback(async (id: number): Promise<boolean> => {
        const apiResponse = await ApiClient.delete(scoringSetDeleteEndpoint(id));
        if (apiResponse.success) {
            ApiClient.showSuccess(__('Scoring Set deleted successfully', 'sim-league-toolkit'));
            await refresh();
        }
        return apiResponse.success;
    }, [refresh]);

    const updateScoringSet = useCallback(async (id: number, data: ScoringSetFormData): Promise<boolean> => {
        const apiResponse = await ApiClient.put(scoringSetPutEndpoint(id), data);
        if (apiResponse.success) {
            ApiClient.showSuccess(__('Scoring Set updated successfully', 'sim-league-toolkit'));
            await refresh();
        }
        return apiResponse.success;
    }, [refresh]);

    const findScoringSet = (id: number): ScoringSet => scoringSets.find(ss => ss.id === id);

    useEffect(() => {
        refresh().then(_ => {
        });
    }, [refresh]);

    return {
        createScoringSet,
        deleteScoringSet,
        findScoringSet,
        scoringSets,
        isLoading,
        refresh,
        updateScoringSet,
    };

};

