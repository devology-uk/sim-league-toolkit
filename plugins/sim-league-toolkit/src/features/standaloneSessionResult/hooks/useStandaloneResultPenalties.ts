import {useQuery} from '@tanstack/react-query';

import {standaloneSessionResultQueryKeys} from '../api/standaloneSessionResultQueryKeys';
import {standaloneSessionResultApi} from '../api/standaloneSessionResultApi';

export const useStandaloneResultPenalties = (resultId: number) => {
    return useQuery({
        queryKey: standaloneSessionResultQueryKeys.penalties(resultId),
        queryFn: () => standaloneSessionResultApi.listPenalties(resultId),
        enabled: resultId > 0,
    });
};
