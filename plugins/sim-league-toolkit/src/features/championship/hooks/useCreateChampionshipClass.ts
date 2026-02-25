import {useMutation, useQueryClient} from '@tanstack/react-query';

import {championshipQueryKeys} from '../api/championshipQueryKeys';
import {championshipApi} from '../api/championshipApi';
import {ChampionshipClassFormData} from '../';

export const useCreateChampionshipClass = (championshipId: number) => {
    const queryClient = useQueryClient();

    return useMutation({
        mutationFn: (data: ChampionshipClassFormData) => championshipApi.createClass(championshipId, data),
        onSuccess: () => {
            queryClient.invalidateQueries({queryKey: championshipQueryKeys.classes(championshipId)}).then(() => {});
            queryClient.invalidateQueries({queryKey: championshipQueryKeys.availableClasses(championshipId)}).then(() => {});
        },
    });
};
