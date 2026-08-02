import {useMutation, useQueryClient} from '@tanstack/react-query';

import {standaloneSessionResultQueryKeys} from '../api/standaloneSessionResultQueryKeys';
import {standaloneSessionResultApi} from '../api/standaloneSessionResultApi';

export const useDeleteStandaloneSessionResult = (eventSessionId: number) => {
    const queryClient = useQueryClient();

    return useMutation({
        mutationFn: (id: number) => standaloneSessionResultApi.delete(id),
        onSuccess: async () => {
            await queryClient.invalidateQueries({queryKey: standaloneSessionResultQueryKeys.byEventSession(eventSessionId)});
        },
    });
};
