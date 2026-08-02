import {useMutation, useQueryClient} from '@tanstack/react-query';

import {standaloneEventQueryKeys} from '../api/standaloneEventQueryKeys';
import {standaloneEventApi} from '../api/standaloneEventApi';

export const useDeleteStandaloneEventClass = (standaloneEventId: number) => {
    const queryClient = useQueryClient();

    return useMutation({
        mutationFn: (eventClassId: number) => standaloneEventApi.deleteClass(standaloneEventId, eventClassId),
        onSuccess: async () => {
            await Promise.all([
                queryClient.invalidateQueries({queryKey: standaloneEventQueryKeys.classes(standaloneEventId)}),
                queryClient.invalidateQueries({queryKey: standaloneEventQueryKeys.availableClasses(standaloneEventId)}),
            ]);
        },
    });
};
