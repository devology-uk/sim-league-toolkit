import {useQuery} from '@tanstack/react-query';

import {trophyQueryKeys} from '../api/trophyQueryKeys';
import {trophyApi} from '../api/trophyApi';

export const useStandaloneEventTrophiesPreview = (standaloneEventId: number) => {
    return useQuery({
        queryKey: trophyQueryKeys.standaloneEventPreview(standaloneEventId),
        queryFn: () => trophyApi.previewStandaloneEventTrophies(standaloneEventId),
        enabled: standaloneEventId > 0,
    });
};
