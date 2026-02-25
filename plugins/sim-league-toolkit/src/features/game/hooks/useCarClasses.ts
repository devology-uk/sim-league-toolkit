import {useQuery} from '@tanstack/react-query';

import {gameApi} from '../api/gameApi';
import {gameQueryKeys} from '../api/gameQueryKeys';

export const useCarClasses = (gameId: number) => {
    return useQuery({
                        queryKey: gameQueryKeys.carClasses(gameId),
                        queryFn: () => gameApi.listCarClasses(gameId),
                        enabled: gameId > 0,
                    });
};