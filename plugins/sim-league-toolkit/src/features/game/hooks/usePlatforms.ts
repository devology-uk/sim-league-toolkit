import {useQuery} from '@tanstack/react-query';

import {gameApi} from '../api/gameApi';
import {gameQueryKeys} from '../api/gameQueryKeys';

export const usePlatforms = (gameId: number) => {
    return useQuery({
                        queryKey: gameQueryKeys.platforms(gameId),
                        queryFn: () => gameApi.listPlatforms(gameId),
                        enabled: gameId > 0,
                    });
};