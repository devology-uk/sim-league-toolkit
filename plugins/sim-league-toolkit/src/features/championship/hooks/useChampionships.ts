import {useQuery} from '@tanstack/react-query';

import {championshipQueryKeys} from '../api/championshipQueryKeys';
import {championshipApi} from '../api/championshipApi';

export const useChampionships = () => {
    return useQuery({
        queryKey: championshipQueryKeys.all,
        queryFn: () => championshipApi.list(),
    });
};
