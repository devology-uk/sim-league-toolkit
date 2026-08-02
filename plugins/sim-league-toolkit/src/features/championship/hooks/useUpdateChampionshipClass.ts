import {useMutation, useQueryClient} from '@tanstack/react-query';

import {championshipQueryKeys} from '../api/championshipQueryKeys';
import {championshipApi} from '../api/championshipApi';

export const useUpdateChampionshipClass = (championshipId: number) => {
    const queryClient = useQueryClient();

    return useMutation({
        mutationFn: ({eventClassId, maxEntrants}: { eventClassId: number; maxEntrants: number | null }) =>
            championshipApi.updateClass(championshipId, eventClassId, maxEntrants),
        onSuccess: async () => {
            await queryClient.invalidateQueries({queryKey: championshipQueryKeys.classes(championshipId)});
        },
    });
};
