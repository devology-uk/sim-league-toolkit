import {useMutation, useQueryClient} from '@tanstack/react-query';

import {championshipQueryKeys} from '../api/championshipQueryKeys';
import {championshipApi} from '../api/championshipApi';
import {ChampionshipEventFormData} from '../';

export const useUpdateChampionshipEvent = (championshipId: number) => {
    const queryClient = useQueryClient();

    return useMutation({
        mutationFn: ({id, data}: { id: number; data: ChampionshipEventFormData }) => championshipApi.updateEvent(id, data),
        onSuccess: (_, {id}) => {
            queryClient.invalidateQueries({queryKey: championshipQueryKeys.events(championshipId)}).then(() => {});
            queryClient.invalidateQueries({queryKey: championshipQueryKeys.event(id)}).then(() => {});
        },
    });
};
