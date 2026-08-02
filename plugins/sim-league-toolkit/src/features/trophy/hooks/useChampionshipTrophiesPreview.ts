import {useQuery} from '@tanstack/react-query';

import {trophyQueryKeys} from '../api/trophyQueryKeys';
import {trophyApi} from '../api/trophyApi';

export const useChampionshipTrophiesPreview = (championshipId: number) => {
    return useQuery({
        queryKey: trophyQueryKeys.championshipPreview(championshipId),
        queryFn: () => trophyApi.previewChampionshipTrophies(championshipId),
        enabled: championshipId > 0,
    });
};
