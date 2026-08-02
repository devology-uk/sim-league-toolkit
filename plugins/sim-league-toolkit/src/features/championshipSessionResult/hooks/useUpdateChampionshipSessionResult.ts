import {useMutation, useQueryClient} from '@tanstack/react-query';

import {championshipSessionResultQueryKeys} from '../api/championshipSessionResultQueryKeys';
import {championshipSessionResultApi} from '../api/championshipSessionResultApi';
import {ChampionshipSessionResultFormData} from '../';

export const useUpdateChampionshipSessionResult = (eventSessionId: number) => {
    const queryClient = useQueryClient();

    return useMutation({
        mutationFn: ({id, data}: {id: number; data: ChampionshipSessionResultFormData}) => championshipSessionResultApi.update(id, data),
        onSuccess: async () => {
            await queryClient.invalidateQueries({queryKey: championshipSessionResultQueryKeys.byEventSession(eventSessionId)});
        },
    });
};
