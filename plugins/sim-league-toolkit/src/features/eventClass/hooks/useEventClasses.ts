import {useQuery} from '@tanstack/react-query';

import {eventClassQueryKeys} from '../api/eventClassQueryKeys';
import {eventClassApi} from '../api/eventClassApi';

export const useEventClasses = () => {
    return useQuery({
        queryKey: eventClassQueryKeys.all,
        queryFn: eventClassApi.list,
    });
};
