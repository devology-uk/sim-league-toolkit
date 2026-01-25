import {__} from '@wordpress/i18n';
import {useState, useCallback, useEffect} from '@wordpress/element';

import {ApiClient} from '../api/ApiClient';
import {CreateResponse} from '../types/CreateResponse';
import {ScoringSetScore} from '../types/ScoringSetScore';
import {ScoringSetScoreFormData} from '../types/ScoringSetScoreFormData';
import {
    scoringSetScoresGetEndpoint,
    scoringSetScoreDeleteEndpoint,
    scoringSetScorePutEndpoint, scoringSetScorePostEndpoint
} from '../api/endpoints/scoringSetApiEndpoints';

interface UseScoringSetScoresResult {
    createScoringSetScore: (scoringSetId: number, data: ScoringSetScoreFormData) => Promise<number | null>;
    deleteScoringSetScore: (id: number) => Promise<boolean>;
    isLoading: boolean;
    refresh: () => Promise<void>
    scoringSetScores: ScoringSetScore[];
    updateScoringSetScore: (id: number, data: ScoringSetScoreFormData) => Promise<boolean>;
}

export const useScoringSetScores = (serverId: number | null): UseScoringSetScoresResult => {
    const [scoringSetScores, setScoringSetScores] = useState<ScoringSetScore[]>([]);
    const [isLoading, setIsLoading] = useState(false);

    const refresh = useCallback(async () => {
        if (!serverId) {
            setScoringSetScores([]);
            return;
        }

        setIsLoading(true);
        const response = await ApiClient.get<ScoringSetScore[]>(
            scoringSetScoresGetEndpoint(serverId)
        );

        if (response.success) {
            setScoringSetScores(response.data ?? []);
        }

        setIsLoading(false);
    }, [serverId]);

    const createScoringSetScore = useCallback(async (scoringSetId: number, data: ScoringSetScoreFormData): Promise<number | null> => {
        const response = await ApiClient.post<CreateResponse>(
            scoringSetScorePostEndpoint(serverId!),
            data
        );

        if (response.success && response.data) {
            ApiClient.showSuccess(__('Scoring Set Score added successfully', 'sim-league-toolkit'));
            await refresh();
            return response.data.id;
        }

        return null;
    }, [serverId, refresh]);

    const deleteScoringSetScore = useCallback(async (id: number): Promise<boolean> => {
        const response = await ApiClient.delete(scoringSetScoreDeleteEndpoint(id));

        if (response.success) {
            ApiClient.showSuccess(__('Scoring Set Score deleted successfully', 'sim-league-toolkit'));
            await refresh();
        }

        return response.success;
    }, [refresh]);

    const updateScoringSetScore = useCallback(async (id: number, data: ScoringSetScoreFormData): Promise<boolean> => {
        const response = await ApiClient.put(scoringSetScorePutEndpoint(id), data);

        if (response.success) {
            ApiClient.showSuccess(__('Scoring Set Score updated successfully', 'sim-league-toolkit'));
            await refresh();
        }

        return response.success;
    }, [refresh]);

    useEffect(() => {
        refresh().then(_ => {});
    }, [refresh]);

    return {
        createScoringSetScore,
        deleteScoringSetScore,
        isLoading,
        refresh,
        scoringSetScores,
        updateScoringSetScore,
    };
};