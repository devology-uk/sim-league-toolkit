import {useMutation, useQueryClient} from '@tanstack/react-query';

import {serverQueryKeys} from '../api/serverQueryKeys';
import {serverApi} from '../api/serverApi';
import {ServerSettingFormData} from '../';

export const useUpdateServerSetting = (serverId: number) => {
    const queryClient = useQueryClient();

    return useMutation({
        mutationFn: ({id, data}: { id: number; data: ServerSettingFormData }) => serverApi.updateSetting(id, data),
        onSuccess: (_, {id}) => {
            queryClient.invalidateQueries({queryKey: serverQueryKeys.settings(serverId)}).then(() => {});
            queryClient.invalidateQueries({queryKey: serverQueryKeys.setting(id)}).then(() => {});
        },
    });
};
