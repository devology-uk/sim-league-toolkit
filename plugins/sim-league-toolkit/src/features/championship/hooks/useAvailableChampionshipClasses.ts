import {useQuery} from '@tanstack/react-query';

import {championshipQueryKeys} from '../api/championshipQueryKeys';
import {championshipApi} from '../api/championshipApi';

export const useAvailableChampionshipClasses = (championshipId: number) => {
    return useQuery({
        queryKey: championshipQueryKeys.availableClasses(championshipId),
        queryFn: () => championshipApi.listAvailableClasses(championshipId),
        enabled: championshipId > 0,
    });
};
