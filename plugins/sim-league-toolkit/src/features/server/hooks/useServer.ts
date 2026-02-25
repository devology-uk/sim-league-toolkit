import {useQuery} from '@tanstack/react-query';

import {serverQueryKeys} from '../api/serverQueryKeys';
import {serverApi} from '../api/serverApi';

export const useServer = (id: number) => {
    return useQuery({
        queryKey: serverQueryKeys.single(id),
        queryFn: () => serverApi.getById(id),
    });
};
