import {useQuery} from '@tanstack/react-query';

import {memberApi} from '../api/memberApi';
import {memberQueryKeys} from '../api/memberQueryKeys';

export const useMembers = () => {
    return useQuery({
        queryKey: memberQueryKeys.all,
        queryFn: () => memberApi.list(),
    });
};
