import {useQuery} from '@tanstack/react-query';

import {gameApi} from '../api/gameApi';
import {gameQueryKeys} from '../api/gameQueryKeys';

export const useGameConfig = (gameKey: string) => {
    return useQuery({
                        queryKey: gameQueryKeys.config(gameKey),
                        queryFn: () => gameApi.getConfig(gameKey),
                        enabled: gameKey?.length > 0,
                    });
};