import {useMutation, useQueryClient} from '@tanstack/react-query';

import {championshipQueryKeys} from '../api/championshipQueryKeys';
import {championshipApi} from '../api/championshipApi';
import {ChampionshipEventFormData} from '../';

export const useCreateChampionshipEvent = (championshipId: number) => {
    const queryClient = useQueryClient();

    return useMutation({
        mutationFn: (data: ChampionshipEventFormData) => championshipApi.createEvent(championshipId, data),
        onSuccess: () => {
            queryClient.invalidateQueries({queryKey: championshipQueryKeys.events(championshipId)}).then(() => {});
        },
    });
};
