import {useMutation, useQueryClient} from '@tanstack/react-query';

import {championshipQueryKeys} from '../api/championshipQueryKeys';
import {championshipApi} from '../api/championshipApi';

export const useDeleteChampionship = () => {
    const queryClient = useQueryClient();

    return useMutation({
        mutationFn: (id: number) => championshipApi.delete(id),
        onSuccess: async () => {
            await queryClient.invalidateQueries({queryKey: championshipQueryKeys.all});
        },
    });
};
