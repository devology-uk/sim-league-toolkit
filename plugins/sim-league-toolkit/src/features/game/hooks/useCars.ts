import {useQuery} from '@tanstack/react-query';

import {gameApi} from '../api/gameApi';
import {gameQueryKeys} from '../api/gameQueryKeys';

export const useCars = (gameId: number, carClass?: string) => {
    const byClass = !!carClass;
    return useQuery({
                        queryKey: byClass
                            ? gameQueryKeys.carsByClass(gameId, carClass!)
                            : gameQueryKeys.cars(gameId),
                        queryFn: () => byClass
                            ? gameApi.listCarsByClass(gameId, carClass!)
                            : gameApi.listCars(gameId),
                        enabled: gameId > 0,
                    });
};