import {useQuery} from '@tanstack/react-query';

import {serverQueryKeys} from '../api/serverQueryKeys';
import {serverApi} from '../api/serverApi';

export const useServerSetting = (id: number) => {
    return useQuery({
        queryKey: serverQueryKeys.setting(id),
        queryFn: () => serverApi.getSettingById(id),
    });
};
