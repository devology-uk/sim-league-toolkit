import {useMutation, useQueryClient} from '@tanstack/react-query';

import {eventSessionQueryKeys} from '../api/eventSessionQueryKeys';
import {eventSessionApi} from '../api/eventSessionApi';

export const useReorderEventSessions = (eventRefId: number) => {
    const queryClient = useQueryClient();

    return useMutation({
        mutationFn: (sessionIds: number[]) => eventSessionApi.reorder(eventRefId, sessionIds),
        onSuccess: () => {
            queryClient.invalidateQueries({queryKey: eventSessionQueryKeys.byEventRef(eventRefId)}).then(() => {});
        },
    });
};
