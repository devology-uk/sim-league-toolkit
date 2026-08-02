import {useMutation, useQueryClient} from '@tanstack/react-query';

import {trophyQueryKeys} from '../api/trophyQueryKeys';
import {trophyApi} from '../api/trophyApi';
import {championshipQueryKeys} from '../../championship/api/championshipQueryKeys';

export const useAwardChampionshipTrophies = () => {
    const queryClient = useQueryClient();

    return useMutation({
        mutationFn: (championshipId: number) => trophyApi.awardChampionshipTrophies(championshipId),
        onSuccess: async (_, championshipId) => {
            await Promise.all([
                queryClient.invalidateQueries({queryKey: trophyQueryKeys.championshipPreview(championshipId)}),
                queryClient.invalidateQueries({queryKey: championshipQueryKeys.all}),
                queryClient.invalidateQueries({queryKey: championshipQueryKeys.single(championshipId)}),
            ]);
        },
    });
};
