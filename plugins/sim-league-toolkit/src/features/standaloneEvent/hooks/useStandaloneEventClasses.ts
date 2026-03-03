import {useQuery} from '@tanstack/react-query';

import {standaloneEventQueryKeys} from '../api/standaloneEventQueryKeys';
import {standaloneEventApi} from '../api/standaloneEventApi';

export const useStandaloneEventClasses = (standaloneEventId: number) => {
    return useQuery({
        queryKey: standaloneEventQueryKeys.classes(standaloneEventId),
        queryFn: () => standaloneEventApi.listClasses(standaloneEventId),
        enabled: standaloneEventId > 0,
    });
};
