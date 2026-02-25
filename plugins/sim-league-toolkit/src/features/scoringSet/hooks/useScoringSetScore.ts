import {useQuery} from '@tanstack/react-query';

import {scoringSetQueryKeys} from '../api/scoringSetQueryKeys';
import {scoringSetApi} from '../api/scoringSetApi';

export const useScoringSetScore = (id: number) => {
    return useQuery({
        queryKey: scoringSetQueryKeys.score(id),
        queryFn: () => scoringSetApi.getScoreById(id),
    });
};
