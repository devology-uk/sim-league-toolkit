import {useMutation, useQueryClient} from '@tanstack/react-query';

import {serverQueryKeys} from '../api/serverQueryKeys';
import {serverApi} from '../api/serverApi';
import {ServerFormData} from '../';

export const useUpdateServer = () => {
    const queryClient = useQueryClient();

    return useMutation({
        mutationFn: ({id, data}: { id: number; data: ServerFormData }) => serverApi.update(id, data),
        onSuccess: (_, {id}) => {
            queryClient.invalidateQueries({queryKey: serverQueryKeys.all}).then(() => {});
            queryClient.invalidateQueries({queryKey: serverQueryKeys.single(id)}).then(() => {});
        },
    });
};
