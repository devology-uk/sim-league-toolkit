import {useMutation, useQueryClient} from '@tanstack/react-query';

import {championshipQueryKeys} from '../api/championshipQueryKeys';
import {championshipApi} from '../api/championshipApi';
import {ChampionshipEventFormData} from '../';

export const useUpdateChampionshipEvent = (championshipId: number) => {
    const queryClient = useQueryClient();

    return useMutation({
        mutationFn: ({id, data}: { id: number; data: ChampionshipEventFormData }) => championshipApi.updateEvent(id, data),
        onSuccess: async (_, {id}) => {
            await Promise.all([
                queryClient.invalidateQueries({queryKey: championshipQueryKeys.events(championshipId)}),
                queryClient.invalidateQueries({queryKey: championshipQueryKeys.event(id)}),
            ]);
        },
    });
};
