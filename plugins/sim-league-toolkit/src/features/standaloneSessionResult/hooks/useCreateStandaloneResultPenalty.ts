import {useMutation, useQueryClient} from '@tanstack/react-query';

import {standaloneSessionResultQueryKeys} from '../api/standaloneSessionResultQueryKeys';
import {standaloneSessionResultApi} from '../api/standaloneSessionResultApi';
import {StandaloneResultPenaltyFormData} from '../';

export const useCreateStandaloneResultPenalty = (resultId: number) => {
    const queryClient = useQueryClient();

    return useMutation({
        mutationFn: (data: StandaloneResultPenaltyFormData) => standaloneSessionResultApi.createPenalty(resultId, data),
        onSuccess: async () => {
            await queryClient.invalidateQueries({queryKey: standaloneSessionResultQueryKeys.penalties(resultId)});
        },
    });
};
