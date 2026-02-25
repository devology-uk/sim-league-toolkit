import {useMutation, useQueryClient} from '@tanstack/react-query';

import {championshipQueryKeys} from '../api/championshipQueryKeys';
import {championshipApi} from '../api/championshipApi';

export const useDeleteChampionshipEvent = (championshipId: number) => {
    const queryClient = useQueryClient();

    return useMutation({
        mutationFn: (id: number) => championshipApi.deleteEvent(id),
        onSuccess: () => {
            queryClient.invalidateQueries({queryKey: championshipQueryKeys.events(championshipId)}).then(() => {});
        },
    });
};
