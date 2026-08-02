import {useMutation, useQueryClient} from '@tanstack/react-query';

import {championshipSessionResultQueryKeys} from '../api/championshipSessionResultQueryKeys';
import {championshipSessionResultApi} from '../api/championshipSessionResultApi';
import {ChampionshipResultPenaltyFormData} from '../';

export const useCreateChampionshipResultPenalty = (resultId: number) => {
    const queryClient = useQueryClient();

    return useMutation({
        mutationFn: (data: ChampionshipResultPenaltyFormData) => championshipSessionResultApi.createPenalty(resultId, data),
        onSuccess: async () => {
            await queryClient.invalidateQueries({queryKey: championshipSessionResultQueryKeys.penalties(resultId)});
        },
    });
};
