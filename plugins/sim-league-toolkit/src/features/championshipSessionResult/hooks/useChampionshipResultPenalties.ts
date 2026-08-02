import {useQuery} from '@tanstack/react-query';

import {championshipSessionResultQueryKeys} from '../api/championshipSessionResultQueryKeys';
import {championshipSessionResultApi} from '../api/championshipSessionResultApi';

export const useChampionshipResultPenalties = (resultId: number) => {
    return useQuery({
        queryKey: championshipSessionResultQueryKeys.penalties(resultId),
        queryFn: () => championshipSessionResultApi.listPenalties(resultId),
        enabled: resultId > 0,
    });
};
