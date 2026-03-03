import {useMutation, useQueryClient} from '@tanstack/react-query';

import {standaloneEventQueryKeys} from '../api/standaloneEventQueryKeys';
import {standaloneEventApi} from '../api/standaloneEventApi';
import {StandaloneEventClassFormData} from '../types/StandaloneEventClassFormData';

export const useCreateStandaloneEventClass = (standaloneEventId: number) => {
    const queryClient = useQueryClient();

    return useMutation({
        mutationFn: (data: StandaloneEventClassFormData) => standaloneEventApi.createClass(standaloneEventId, data),
        onSuccess: () => {
            queryClient.invalidateQueries({queryKey: standaloneEventQueryKeys.classes(standaloneEventId)}).then(() => {});
            queryClient.invalidateQueries({queryKey: standaloneEventQueryKeys.availableClasses(standaloneEventId)}).then(() => {});
        },
    });
};
