import {useMutation, useQueryClient} from '@tanstack/react-query';

import {championshipSessionResultQueryKeys} from '../api/championshipSessionResultQueryKeys';
import {championshipSessionResultApi} from '../api/championshipSessionResultApi';

export const useDeleteChampionshipResultPenalty = (resultId: number) => {
    const queryClient = useQueryClient();

    return useMutation({
        mutationFn: (id: number) => championshipSessionResultApi.deletePenalty(id),
        onSuccess: async () => {
            await queryClient.invalidateQueries({queryKey: championshipSessionResultQueryKeys.penalties(resultId)});
        },
    });
};
