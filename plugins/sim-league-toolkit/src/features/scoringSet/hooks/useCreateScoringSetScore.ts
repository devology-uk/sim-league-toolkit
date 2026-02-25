import {useMutation, useQueryClient} from '@tanstack/react-query';

import {scoringSetQueryKeys} from '../api/scoringSetQueryKeys';
import {scoringSetApi} from '../api/scoringSetApi';
import {ScoringSetScoreFormData} from '../';

export const useCreateScoringSetScore = (scoringSetId: number) => {
    const queryClient = useQueryClient();

    return useMutation({
        mutationFn: (data: ScoringSetScoreFormData) => scoringSetApi.createScore(scoringSetId, data),
        onSuccess: () => {
            queryClient.invalidateQueries({queryKey: scoringSetQueryKeys.scores(scoringSetId)}).then(() => {});
        },
    });
};
