import {useMutation, useQueryClient} from '@tanstack/react-query';

import {eventClassQueryKeys} from '../api/eventClassQueryKeys';
import {eventClassApi} from '../api/eventClassApi';
import {EventClassFormData} from '../';

export const useUpdateEventClass = () => {
    const queryClient = useQueryClient();

    return useMutation({
        mutationFn: ({id, data}: { id: number; data: EventClassFormData }) => eventClassApi.update(id, data),
        onSuccess: (_, {id}) => {
            queryClient.invalidateQueries({queryKey: eventClassQueryKeys.all}).then(() => {});
            queryClient.invalidateQueries({queryKey: eventClassQueryKeys.single(id)}).then(() => {});
        },
    });
};
