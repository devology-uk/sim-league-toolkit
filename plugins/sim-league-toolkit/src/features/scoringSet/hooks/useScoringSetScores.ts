import {useQuery} from '@tanstack/react-query';

import {scoringSetQueryKeys} from '../api/scoringSetQueryKeys';
import {scoringSetApi} from '../api/scoringSetApi';

export const useScoringSetScores = (scoringSetId: number) => {
    return useQuery({
        queryKey: scoringSetQueryKeys.scores(scoringSetId),
        queryFn: () => scoringSetApi.listScores(scoringSetId),
    });
};
