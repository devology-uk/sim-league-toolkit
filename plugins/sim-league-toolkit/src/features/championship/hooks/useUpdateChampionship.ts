import {useMutation, useQueryClient} from '@tanstack/react-query';

import {championshipQueryKeys} from '../api/championshipQueryKeys';
import {championshipApi} from '../api/championshipApi';
import {ChampionshipFormData} from '../';

export const useUpdateChampionship = () => {
    const queryClient = useQueryClient();

    return useMutation({
        mutationFn: ({id, data}: { id: number; data: ChampionshipFormData }) => championshipApi.update(id, data),
        onSuccess: async (_, {id}) => {
            await Promise.all([
                queryClient.invalidateQueries({queryKey: championshipQueryKeys.all}),
                queryClient.invalidateQueries({queryKey: championshipQueryKeys.single(id)}),
            ]);
        },
    });
};
