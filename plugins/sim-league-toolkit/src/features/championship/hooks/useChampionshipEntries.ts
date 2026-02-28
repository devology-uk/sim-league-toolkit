import {useQuery} from '@tanstack/react-query';

import {championshipQueryKeys} from '../api/championshipQueryKeys';
import {championshipApi} from '../api/championshipApi';

export const useChampionshipEntries = (championshipId: number) => {
    return useQuery({
        queryKey: championshipQueryKeys.entries(championshipId),
        queryFn: () => championshipApi.listEntries(championshipId),
        enabled: championshipId > 0,
    });
};
