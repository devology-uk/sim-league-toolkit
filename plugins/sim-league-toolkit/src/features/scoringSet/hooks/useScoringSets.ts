import {useQuery} from '@tanstack/react-query';

import {scoringSetQueryKeys} from '../api/scoringSetQueryKeys';
import {scoringSetApi} from '../api/scoringSetApi';

export const useScoringSets = () => {
    return useQuery({
        queryKey: scoringSetQueryKeys.all,
        queryFn: scoringSetApi.list,
    });
};
