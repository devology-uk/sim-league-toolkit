import {useQuery} from '@tanstack/react-query';

import {championshipQueryKeys} from '../api/championshipQueryKeys';
import {championshipApi} from '../api/championshipApi';

export const useChampionshipClasses = (championshipId: number) => {
    return useQuery({
        queryKey: championshipQueryKeys.classes(championshipId),
        queryFn: () => championshipApi.listClasses(championshipId),
        enabled: championshipId > 0,
    });
};
