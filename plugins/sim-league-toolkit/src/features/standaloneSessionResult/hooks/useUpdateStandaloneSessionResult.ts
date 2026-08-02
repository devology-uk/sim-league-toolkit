import {useMutation, useQueryClient} from '@tanstack/react-query';

import {standaloneSessionResultQueryKeys} from '../api/standaloneSessionResultQueryKeys';
import {standaloneSessionResultApi} from '../api/standaloneSessionResultApi';
import {StandaloneSessionResultFormData} from '../';

export const useUpdateStandaloneSessionResult = (eventSessionId: number) => {
    const queryClient = useQueryClient();

    return useMutation({
        mutationFn: ({id, data}: {id: number; data: StandaloneSessionResultFormData}) => standaloneSessionResultApi.update(id, data),
        onSuccess: async () => {
            await queryClient.invalidateQueries({queryKey: standaloneSessionResultQueryKeys.byEventSession(eventSessionId)});
        },
    });
};
