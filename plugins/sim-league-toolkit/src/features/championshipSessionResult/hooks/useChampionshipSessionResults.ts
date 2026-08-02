import {useQuery} from '@tanstack/react-query';

import {championshipSessionResultQueryKeys} from '../api/championshipSessionResultQueryKeys';
import {championshipSessionResultApi} from '../api/championshipSessionResultApi';

export const useChampionshipSessionResults = (eventSessionId: number) => {
    return useQuery({
        queryKey: championshipSessionResultQueryKeys.byEventSession(eventSessionId),
        queryFn: () => championshipSessionResultApi.listByEventSession(eventSessionId),
        enabled: eventSessionId > 0,
    });
};
