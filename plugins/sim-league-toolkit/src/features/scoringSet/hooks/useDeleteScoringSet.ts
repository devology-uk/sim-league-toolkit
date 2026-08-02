import {useMutation, useQueryClient} from '@tanstack/react-query';

import {scoringSetQueryKeys} from '../api/scoringSetQueryKeys';
import {scoringSetApi} from '../api/scoringSetApi';

export const useDeleteScoringSet = () => {
    const queryClient = useQueryClient();

    return useMutation({
        mutationFn: (id: number) => scoringSetApi.delete(id),
        onSuccess: async () => {
            await queryClient.invalidateQueries({queryKey: scoringSetQueryKeys.all});
        },
    });
};
