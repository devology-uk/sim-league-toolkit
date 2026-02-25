import {useMutation, useQueryClient} from '@tanstack/react-query';

import {championshipQueryKeys} from '../api/championshipQueryKeys';
import {championshipApi} from '../api/championshipApi';

export const useDeleteChampionshipClass = (championshipId: number) => {
    const queryClient = useQueryClient();

    return useMutation({
        mutationFn: (eventClassId: number) => championshipApi.deleteClass(championshipId, eventClassId),
        onSuccess: () => {
            queryClient.invalidateQueries({queryKey: championshipQueryKeys.classes(championshipId)}).then(() => {});
            queryClient.invalidateQueries({queryKey: championshipQueryKeys.availableClasses(championshipId)}).then(() => {});
        },
    });
};
