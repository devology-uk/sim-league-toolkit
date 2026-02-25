import {useQuery} from '@tanstack/react-query';

import {championshipQueryKeys} from '../api/championshipQueryKeys';
import {championshipApi} from '../api/championshipApi';

export const useChampionship = (id: number) => {
    return useQuery({
        queryKey: championshipQueryKeys.single(id),
        queryFn: () => championshipApi.getById(id),
        enabled: id > 0,
    });
};
