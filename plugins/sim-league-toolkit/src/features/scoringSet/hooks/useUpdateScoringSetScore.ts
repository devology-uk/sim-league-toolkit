import {useMutation, useQueryClient} from '@tanstack/react-query';

import {scoringSetQueryKeys} from '../api/scoringSetQueryKeys';
import {scoringSetApi} from '../api/scoringSetApi';
import {ScoringSetScoreFormData} from '../';

export const useUpdateScoringSetScore = (scoringSetId: number) => {
    const queryClient = useQueryClient();

    return useMutation({
        mutationFn: ({id, data}: { id: number; data: ScoringSetScoreFormData }) => scoringSetApi.updateScore(id, data),
        onSuccess: async (_, {id}) => {
            await Promise.all([
                queryClient.invalidateQueries({queryKey: scoringSetQueryKeys.scores(scoringSetId)}),
                queryClient.invalidateQueries({queryKey: scoringSetQueryKeys.score(id)}),
            ]);
        },
    });
};
