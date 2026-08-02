import {useMutation, useQueryClient} from '@tanstack/react-query';

import {scoringSetQueryKeys} from '../api/scoringSetQueryKeys';
import {scoringSetApi} from '../api/scoringSetApi';

export const useDeleteScoringSetScore = (scoringSetId: number) => {
    const queryClient = useQueryClient();

    return useMutation({
        mutationFn: (id: number) => scoringSetApi.deleteScore(id),
        onSuccess: async () => {
            await queryClient.invalidateQueries({queryKey: scoringSetQueryKeys.scores(scoringSetId)});
        },
    });
};
