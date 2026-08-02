import {useMutation, useQueryClient} from '@tanstack/react-query';

import {standaloneEventQueryKeys} from '../api/standaloneEventQueryKeys';
import {standaloneEventApi} from '../api/standaloneEventApi';

export const useDeleteStandaloneEvent = () => {
    const queryClient = useQueryClient();

    return useMutation({
        mutationFn: (id: number) => standaloneEventApi.delete(id),
        onSuccess: async () => {
            await queryClient.invalidateQueries({queryKey: standaloneEventQueryKeys.all});
        },
    });
};
