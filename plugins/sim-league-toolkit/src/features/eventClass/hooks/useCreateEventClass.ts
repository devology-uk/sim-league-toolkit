import {useMutation, useQueryClient} from '@tanstack/react-query';

import {eventClassQueryKeys} from '../api/eventClassQueryKeys';
import {eventClassApi} from '../api/eventClassApi';
import {EventClassFormData} from '../';

export const useCreateEventClass = () => {
    const queryClient = useQueryClient();

    return useMutation({
        mutationFn: (data: EventClassFormData) => eventClassApi.create(data),
        onSuccess: () => {
            queryClient.invalidateQueries({queryKey: eventClassQueryKeys.all}).then(() => {});
        },
    });
};
