import {useMutation, useQueryClient} from '@tanstack/react-query';

import {standaloneEventQueryKeys} from '../api/standaloneEventQueryKeys';
import {standaloneEventApi} from '../api/standaloneEventApi';
import {StandaloneEventEntryFormData} from '../types/StandaloneEventEntryFormData';

export const useCreateStandaloneEventEntry = (standaloneEventId: number) => {
    const queryClient = useQueryClient();

    return useMutation({
        mutationFn: (data: StandaloneEventEntryFormData) => standaloneEventApi.createEntry(standaloneEventId, data),
        onSuccess: () => {
            queryClient.invalidateQueries({queryKey: standaloneEventQueryKeys.entries(standaloneEventId)}).then(() => {});
        },
    });
};
