import {useQuery} from '@tanstack/react-query';

import {eventClassQueryKeys} from '../api/eventClassQueryKeys';
import {eventClassApi} from '../api/eventClassApi';

export const useEventClass = (id: number) => {
    return useQuery({
        queryKey: eventClassQueryKeys.single(id),
        queryFn: () => eventClassApi.getById(id),
    });
};
