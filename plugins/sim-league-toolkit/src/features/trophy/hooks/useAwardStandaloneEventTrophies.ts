import {useMutation, useQueryClient} from '@tanstack/react-query';

import {trophyQueryKeys} from '../api/trophyQueryKeys';
import {trophyApi} from '../api/trophyApi';
import {standaloneEventQueryKeys} from '../../standaloneEvent/api/standaloneEventQueryKeys';

export const useAwardStandaloneEventTrophies = () => {
    const queryClient = useQueryClient();

    return useMutation({
        mutationFn: (standaloneEventId: number) => trophyApi.awardStandaloneEventTrophies(standaloneEventId),
        onSuccess: async (_, standaloneEventId) => {
            await Promise.all([
                queryClient.invalidateQueries({queryKey: trophyQueryKeys.standaloneEventPreview(standaloneEventId)}),
                queryClient.invalidateQueries({queryKey: standaloneEventQueryKeys.all}),
                queryClient.invalidateQueries({queryKey: standaloneEventQueryKeys.single(standaloneEventId)}),
            ]);
        },
    });
};
