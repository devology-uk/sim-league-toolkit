import {useMutation, useQueryClient} from '@tanstack/react-query';

import {standaloneEventQueryKeys} from '../api/standaloneEventQueryKeys';
import {standaloneEventApi} from '../api/standaloneEventApi';

export const useUpdateStandaloneEventClass = (standaloneEventId: number) => {
    const queryClient = useQueryClient();

    return useMutation({
        mutationFn: ({eventClassId, maxEntrants}: { eventClassId: number; maxEntrants: number | null }) =>
            standaloneEventApi.updateClass(standaloneEventId, eventClassId, maxEntrants),
        onSuccess: async () => {
            await queryClient.invalidateQueries({queryKey: standaloneEventQueryKeys.classes(standaloneEventId)});
        },
    });
};
