import {useQuery} from '@tanstack/react-query';

import {serverQueryKeys} from '../api/serverQueryKeys';
import {serverApi} from '../api/serverApi';

export const useServerSettings = (serverId: number) => {
    return useQuery({
        queryKey: serverQueryKeys.settings(serverId),
        queryFn: () => serverApi.listSettings(serverId),
    });
};
