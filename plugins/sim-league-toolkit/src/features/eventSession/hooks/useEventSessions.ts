import {useQuery} from '@tanstack/react-query';

import {eventSessionQueryKeys} from '../api/eventSessionQueryKeys';
import {eventSessionApi} from '../api/eventSessionApi';

export const useEventSessions = (eventRefId: number) => {
    return useQuery({
        queryKey: eventSessionQueryKeys.byEventRef(eventRefId),
        queryFn: () => eventSessionApi.listByEventRefId(eventRefId),
        enabled: eventRefId > 0,
    });
};
