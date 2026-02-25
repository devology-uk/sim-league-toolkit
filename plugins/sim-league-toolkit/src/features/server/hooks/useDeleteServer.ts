import {useMutation, useQueryClient} from '@tanstack/react-query';

import {serverQueryKeys} from '../api/serverQueryKeys';
import {serverApi} from '../api/serverApi';

export const useDeleteServer = () => {
    const queryClient = useQueryClient();

    return useMutation({
        mutationFn: (id: number) => serverApi.delete(id),
        onSuccess: () => {
            queryClient.invalidateQueries({queryKey: serverQueryKeys.all}).then(() => {});
        },
    });
};
