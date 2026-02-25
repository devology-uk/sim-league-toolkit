import {useQuery} from '@tanstack/react-query';

import {scoringSetQueryKeys} from '../api/scoringSetQueryKeys';
import {scoringSetApi} from '../api/scoringSetApi';

export const useScoringSet = (id: number) => {
    return useQuery({
        queryKey: scoringSetQueryKeys.single(id),
        queryFn: () => scoringSetApi.getById(id),
    });
};
