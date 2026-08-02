import {useQuery} from '@tanstack/react-query';

import {standaloneSessionResultQueryKeys} from '../api/standaloneSessionResultQueryKeys';
import {standaloneSessionResultApi} from '../api/standaloneSessionResultApi';

export const useStandaloneSessionResults = (eventSessionId: number) => {
    return useQuery({
        queryKey: standaloneSessionResultQueryKeys.byEventSession(eventSessionId),
        queryFn: () => standaloneSessionResultApi.listByEventSession(eventSessionId),
        enabled: eventSessionId > 0,
    });
};
