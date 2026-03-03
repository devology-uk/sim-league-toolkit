import {useQuery} from '@tanstack/react-query';

import {standaloneEventQueryKeys} from '../api/standaloneEventQueryKeys';
import {standaloneEventApi} from '../api/standaloneEventApi';

export const useStandaloneEventEntries = (standaloneEventId: number) => {
    return useQuery({
        queryKey: standaloneEventQueryKeys.entries(standaloneEventId),
        queryFn: () => standaloneEventApi.listEntries(standaloneEventId),
        enabled: standaloneEventId > 0,
    });
};
