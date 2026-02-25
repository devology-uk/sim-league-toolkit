import {useQuery} from '@tanstack/react-query';

import {gameApi} from '../api/gameApi';
import {gameQueryKeys} from '../api/gameQueryKeys';

export const useTracks = (gameId: number) => {
    return useQuery({
                        queryKey: gameQueryKeys.tracks(gameId),
                        queryFn: () => gameApi.listTracks(gameId),
                        enabled: gameId > 0,
                    });
};