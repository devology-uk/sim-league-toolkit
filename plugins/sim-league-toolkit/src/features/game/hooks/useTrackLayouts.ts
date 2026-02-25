import {useQuery} from '@tanstack/react-query';

import {gameApi} from '../api/gameApi';
import {gameQueryKeys} from '../api/gameQueryKeys';

export const useTrackLayouts = (trackId: number) => {
    return useQuery({
                        queryKey: gameQueryKeys.trackLayouts(trackId),
                        queryFn: () => gameApi.listTrackLayouts(trackId),
                        enabled: trackId > 0,
                    });
};