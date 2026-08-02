import {useMutation, useQueryClient} from '@tanstack/react-query';

import {championshipSessionResultQueryKeys} from '../api/championshipSessionResultQueryKeys';
import {championshipSessionResultApi} from '../api/championshipSessionResultApi';

export const useDeleteChampionshipSessionResult = (eventSessionId: number) => {
    const queryClient = useQueryClient();

    return useMutation({
        mutationFn: (id: number) => championshipSessionResultApi.delete(id),
        onSuccess: async () => {
            await queryClient.invalidateQueries({queryKey: championshipSessionResultQueryKeys.byEventSession(eventSessionId)});
        },
    });
};
