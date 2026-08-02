import {useMutation, useQueryClient} from '@tanstack/react-query';

import {eventClassQueryKeys} from '../api/eventClassQueryKeys';
import {eventClassApi} from '../api/eventClassApi';

export const useDeleteEventClass = () => {
    const queryClient = useQueryClient();

    return useMutation({
        mutationFn: (id: number) => eventClassApi.delete(id),
        onSuccess: async () => {
            await queryClient.invalidateQueries({queryKey: eventClassQueryKeys.all});
        },
    });
};
