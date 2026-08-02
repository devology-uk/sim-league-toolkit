import {useMutation, useQueryClient} from '@tanstack/react-query';

import {standaloneSessionResultQueryKeys} from '../api/standaloneSessionResultQueryKeys';
import {standaloneSessionResultApi} from '../api/standaloneSessionResultApi';

export const useDeleteStandaloneResultPenalty = (resultId: number) => {
    const queryClient = useQueryClient();

    return useMutation({
        mutationFn: (id: number) => standaloneSessionResultApi.deletePenalty(id),
        onSuccess: async () => {
            await queryClient.invalidateQueries({queryKey: standaloneSessionResultQueryKeys.penalties(resultId)});
        },
    });
};
