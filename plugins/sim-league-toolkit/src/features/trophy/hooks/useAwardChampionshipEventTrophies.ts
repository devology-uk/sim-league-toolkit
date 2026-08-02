import {useMutation, useQueryClient} from '@tanstack/react-query';

import {trophyQueryKeys} from '../api/trophyQueryKeys';
import {trophyApi} from '../api/trophyApi';
import {championshipQueryKeys} from '../../championship/api/championshipQueryKeys';

export const useAwardChampionshipEventTrophies = (championshipId: number) => {
    const queryClient = useQueryClient();

    return useMutation({
        mutationFn: (championshipEventId: number) => trophyApi.awardChampionshipEventTrophies(championshipEventId),
        onSuccess: async (_, championshipEventId) => {
            await Promise.all([
                queryClient.invalidateQueries({queryKey: trophyQueryKeys.championshipEventPreview(championshipEventId)}),
                queryClient.invalidateQueries({queryKey: championshipQueryKeys.event(championshipEventId)}),
                queryClient.invalidateQueries({queryKey: championshipQueryKeys.events(championshipId)}),
            ]);
        },
    });
};
