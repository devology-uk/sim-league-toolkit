import {useMutation, useQueryClient} from '@tanstack/react-query';

import {standaloneSessionResultQueryKeys} from '../api/standaloneSessionResultQueryKeys';
import {standaloneSessionResultApi} from '../api/standaloneSessionResultApi';
import {StandaloneSessionResultFormData} from '../';

export const useCreateStandaloneSessionResult = (eventSessionId: number) => {
    const queryClient = useQueryClient();

    return useMutation({
        mutationFn: (data: StandaloneSessionResultFormData) => standaloneSessionResultApi.create(eventSessionId, data),
        onSuccess: async () => {
            await queryClient.invalidateQueries({queryKey: standaloneSessionResultQueryKeys.byEventSession(eventSessionId)});
        },
    });
};
