import {useMutation, useQueryClient} from '@tanstack/react-query';

import {championshipQueryKeys} from '../api/championshipQueryKeys';
import {championshipApi} from '../api/championshipApi';
import {ChampionshipFormData} from '../';

export const useUpdateChampionship = () => {
    const queryClient = useQueryClient();

    return useMutation({
        mutationFn: ({id, data}: { id: number; data: ChampionshipFormData }) => championshipApi.update(id, data),
        onSuccess: (_, {id}) => {
            queryClient.invalidateQueries({queryKey: championshipQueryKeys.all}).then(() => {});
            queryClient.invalidateQueries({queryKey: championshipQueryKeys.single(id)}).then(() => {});
        },
    });
};
