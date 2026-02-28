import {useMutation, useQueryClient} from '@tanstack/react-query';

import {championshipQueryKeys} from '../api/championshipQueryKeys';
import {championshipApi} from '../api/championshipApi';
import {ChampionshipEntryFormData} from '../';

export const useCreateChampionshipEntry = (championshipId: number) => {
    const queryClient = useQueryClient();

    return useMutation({
        mutationFn: (data: ChampionshipEntryFormData) => championshipApi.createEntry(championshipId, data),
        onSuccess: () => {
            queryClient.invalidateQueries({queryKey: championshipQueryKeys.entries(championshipId)}).then(() => {});
        },
    });
};
