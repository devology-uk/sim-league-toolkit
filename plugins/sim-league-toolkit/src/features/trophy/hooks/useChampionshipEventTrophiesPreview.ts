import {useQuery} from '@tanstack/react-query';

import {trophyQueryKeys} from '../api/trophyQueryKeys';
import {trophyApi} from '../api/trophyApi';

export const useChampionshipEventTrophiesPreview = (championshipEventId: number) => {
    return useQuery({
        queryKey: trophyQueryKeys.championshipEventPreview(championshipEventId),
        queryFn: () => trophyApi.previewChampionshipEventTrophies(championshipEventId),
        enabled: championshipEventId > 0,
    });
};
