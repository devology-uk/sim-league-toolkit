import {useMutation, useQueryClient} from '@tanstack/react-query';

import {eventSessionQueryKeys} from '../api/eventSessionQueryKeys';
import {eventSessionApi} from '../api/eventSessionApi';
import {EventSessionFormData} from '../';

export const useCreateEventSession = (eventRefId: number) => {
    const queryClient = useQueryClient();

    return useMutation({
        mutationFn: (data: EventSessionFormData) => eventSessionApi.create(data),
        onSuccess: () => {
            queryClient.invalidateQueries({queryKey: eventSessionQueryKeys.byEventRef(eventRefId)}).then(() => {});
        },
    });
};
