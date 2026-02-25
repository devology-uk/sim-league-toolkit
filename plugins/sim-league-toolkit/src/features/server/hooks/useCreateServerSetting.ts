import {useMutation, useQueryClient} from '@tanstack/react-query';

import {serverQueryKeys} from '../api/serverQueryKeys';
import {serverApi} from '../api/serverApi';
import {ServerSettingFormData} from '../';

export const useCreateServerSetting = (serverId: number) => {
    const queryClient = useQueryClient();

    return useMutation({
        mutationFn: (data: ServerSettingFormData) => serverApi.createSetting(serverId, data),
        onSuccess: () => {
            queryClient.invalidateQueries({queryKey: serverQueryKeys.settings(serverId)}).then(() => {});
        },
    });
};
