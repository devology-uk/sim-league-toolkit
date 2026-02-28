import {useQuery} from '@tanstack/react-query';

import {standaloneEventQueryKeys} from '../api/standaloneEventQueryKeys';
import {standaloneEventApi} from '../api/standaloneEventApi';

export const useStandaloneEvent = (id: number) => {
    return useQuery({
        queryKey: standaloneEventQueryKeys.single(id),
        queryFn: () => standaloneEventApi.getById(id),
        enabled: id > 0,
    });
};
