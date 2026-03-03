import {useQuery} from '@tanstack/react-query';

import {standaloneEventQueryKeys} from '../api/standaloneEventQueryKeys';
import {standaloneEventApi} from '../api/standaloneEventApi';

export const useAvailableStandaloneEventClasses = (standaloneEventId: number) => {
    return useQuery({
        queryKey: standaloneEventQueryKeys.availableClasses(standaloneEventId),
        queryFn: () => standaloneEventApi.listAvailableClasses(standaloneEventId),
        enabled: standaloneEventId > 0,
    });
};
