import {useMutation, useQueryClient} from '@tanstack/react-query';

import {standaloneEventQueryKeys} from '../api/standaloneEventQueryKeys';
import {standaloneEventApi} from '../api/standaloneEventApi';

export const useDeleteStandaloneEventClass = (standaloneEventId: number) => {
    const queryClient = useQueryClient();

    return useMutation({
        mutationFn: (eventClassId: number) => standaloneEventApi.deleteClass(standaloneEventId, eventClassId),
        onSuccess: () => {
            queryClient.invalidateQueries({queryKey: standaloneEventQueryKeys.classes(standaloneEventId)}).then(() => {});
            queryClient.invalidateQueries({queryKey: standaloneEventQueryKeys.availableClasses(standaloneEventId)}).then(() => {});
        },
    });
};
