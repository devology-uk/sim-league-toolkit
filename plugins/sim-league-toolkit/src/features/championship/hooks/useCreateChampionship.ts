import {useMutation, useQueryClient} from '@tanstack/react-query';

import {championshipQueryKeys} from '../api/championshipQueryKeys';
import {championshipApi} from '../api/championshipApi';
import {ChampionshipFormData} from '../';

export const useCreateChampionship = () => {
    const queryClient = useQueryClient();

    return useMutation({
        mutationFn: (data: ChampionshipFormData) => championshipApi.create(data),
        onSuccess: () => {
            queryClient.invalidateQueries({queryKey: championshipQueryKeys.all}).then(() => {});
        },
    });
};
