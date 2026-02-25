import {useQuery} from '@tanstack/react-query';

import {serverQueryKeys} from '../api/serverQueryKeys';
import {serverApi} from '../api/serverApi';

export const useServers = () => {
    return useQuery({
        queryKey: serverQueryKeys.all,
        queryFn: serverApi.list,
    });
};
