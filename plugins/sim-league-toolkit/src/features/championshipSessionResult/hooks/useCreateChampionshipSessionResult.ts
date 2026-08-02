import {useMutation, useQueryClient} from '@tanstack/react-query';

import {championshipSessionResultQueryKeys} from '../api/championshipSessionResultQueryKeys';
import {championshipSessionResultApi} from '../api/championshipSessionResultApi';
import {ChampionshipSessionResultFormData} from '../';

export const useCreateChampionshipSessionResult = (eventSessionId: number) => {
    const queryClient = useQueryClient();

    return useMutation({
        mutationFn: (data: ChampionshipSessionResultFormData) => championshipSessionResultApi.create(eventSessionId, data),
        onSuccess: async () => {
            await queryClient.invalidateQueries({queryKey: championshipSessionResultQueryKeys.byEventSession(eventSessionId)});
        },
    });
};
