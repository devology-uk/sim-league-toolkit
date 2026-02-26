import {useMutation, useQueryClient} from '@tanstack/react-query';

import {eventSessionQueryKeys} from '../api/eventSessionQueryKeys';
import {eventSessionApi} from '../api/eventSessionApi';
import {EventSessionFormData} from '../';

export const useUpdateEventSession = (eventRefId: number) => {
    const queryClient = useQueryClient();

    return useMutation({
        mutationFn: ({id, data}: {id: number; data: EventSessionFormData}) => eventSessionApi.update(id, data),
        onSuccess: (_, {id}) => {
            queryClient.invalidateQueries({queryKey: eventSessionQueryKeys.byEventRef(eventRefId)}).then(() => {});
            queryClient.invalidateQueries({queryKey: eventSessionQueryKeys.single(id)}).then(() => {});
        },
    });
};
