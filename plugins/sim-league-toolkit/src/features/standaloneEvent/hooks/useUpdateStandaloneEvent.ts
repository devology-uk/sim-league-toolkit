import {useMutation, useQueryClient} from '@tanstack/react-query';

import {standaloneEventQueryKeys} from '../api/standaloneEventQueryKeys';
import {standaloneEventApi} from '../api/standaloneEventApi';
import {StandaloneEventFormData} from '../types/StandaloneEventFormData';

export const useUpdateStandaloneEvent = () => {
    const queryClient = useQueryClient();

    return useMutation({
        mutationFn: ({id, data}: {id: number; data: StandaloneEventFormData}) => standaloneEventApi.update(id, data),
        onSuccess: async (_, {id}) => {
            await queryClient.invalidateQueries({queryKey: standaloneEventQueryKeys.all});
            queryClient.invalidateQueries({queryKey: standaloneEventQueryKeys.single(id)}).then(() => {});
        },
    });
};
