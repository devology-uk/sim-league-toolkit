import {useMutation, useQueryClient} from '@tanstack/react-query';

import {standaloneEventQueryKeys} from '../api/standaloneEventQueryKeys';
import {standaloneEventApi} from '../api/standaloneEventApi';
import {StandaloneEventFormData} from '../types/StandaloneEventFormData';

export const useCreateStandaloneEvent = () => {
    const queryClient = useQueryClient();

    return useMutation({
        mutationFn: (data: StandaloneEventFormData) => standaloneEventApi.create(data),
        onSuccess: () => {
            queryClient.invalidateQueries({queryKey: standaloneEventQueryKeys.all}).then(() => {});
        },
    });
};
