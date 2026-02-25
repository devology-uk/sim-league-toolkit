import {useMutation, useQueryClient} from '@tanstack/react-query';

import {serverQueryKeys} from '../api/serverQueryKeys';
import {serverApi} from '../api/serverApi';
import {ServerFormData} from '../';

export const useCreateServer = () => {
    const queryClient = useQueryClient();

    return useMutation({
        mutationFn: (data: ServerFormData) => serverApi.create(data),
        onSuccess: () => {
            queryClient.invalidateQueries({queryKey: serverQueryKeys.all}).then(() => {});
        },
    });
};
