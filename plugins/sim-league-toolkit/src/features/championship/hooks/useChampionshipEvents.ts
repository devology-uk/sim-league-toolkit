import {useQuery} from '@tanstack/react-query';

import {championshipQueryKeys} from '../api/championshipQueryKeys';
import {championshipApi} from '../api/championshipApi';

export const useChampionshipEvents = (championshipId: number) => {
    return useQuery({
        queryKey: championshipQueryKeys.events(championshipId),
        queryFn: () => championshipApi.listEvents(championshipId),
        enabled: championshipId > 0,
    });
};
