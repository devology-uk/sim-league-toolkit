import {useQuery} from '@tanstack/react-query';

import {standaloneEventQueryKeys} from '../api/standaloneEventQueryKeys';
import {standaloneEventApi} from '../api/standaloneEventApi';

export const useStandaloneEvents = () => {
    return useQuery({
        queryKey: standaloneEventQueryKeys.all,
        queryFn: () => standaloneEventApi.list(),
    });
};
