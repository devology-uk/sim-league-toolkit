import {useMutation, useQueryClient} from '@tanstack/react-query';

import {eventSessionQueryKeys} from '../api/eventSessionQueryKeys';
import {eventSessionApi} from '../api/eventSessionApi';

export const useDeleteEventSession = (eventRefId: number) => {
    const queryClient = useQueryClient();

    return useMutation({
        mutationFn: (id: number) => eventSessionApi.delete(id),
        onSuccess: async () => {
            await queryClient.invalidateQueries({queryKey: eventSessionQueryKeys.byEventRef(eventRefId)});
        },
    });
};
