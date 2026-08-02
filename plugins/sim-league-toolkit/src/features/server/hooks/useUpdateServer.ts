import {useMutation, useQueryClient} from '@tanstack/react-query';

import {serverQueryKeys} from '../api/serverQueryKeys';
import {serverApi} from '../api/serverApi';
import {ServerFormData} from '../';

export const useUpdateServer = () => {
    const queryClient = useQueryClient();

    return useMutation({
        mutationFn: ({id, data}: { id: number; data: ServerFormData }) => serverApi.update(id, data),
        onSuccess: async (_, {id}) => {
            await Promise.all([
                queryClient.invalidateQueries({queryKey: serverQueryKeys.all}),
                queryClient.invalidateQueries({queryKey: serverQueryKeys.single(id)}),
            ]);
        },
    });
};
