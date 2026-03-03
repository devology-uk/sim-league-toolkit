import {useMutation, useQueryClient} from '@tanstack/react-query';

import {standaloneEventQueryKeys} from '../api/standaloneEventQueryKeys';
import {standaloneEventApi} from '../api/standaloneEventApi';

export const useDeleteStandaloneEventEntry = (standaloneEventId: number) => {
    const queryClient = useQueryClient();

    return useMutation({
        mutationFn: (id: number) => standaloneEventApi.deleteEntry(id),
        onSuccess: () => {
            queryClient.invalidateQueries({queryKey: standaloneEventQueryKeys.entries(standaloneEventId)}).then(() => {});
        },
    });
};
