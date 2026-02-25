import {useQuery} from '@tanstack/react-query';

import {gameApi} from '../api/gameApi';
import {gameQueryKeys} from '../api/gameQueryKeys';

export const useGame = (id: number) => {
    return useQuery({
                        queryKey: gameQueryKeys.single(id),
                        queryFn: () => gameApi.getById(id),
                        enabled: id > 0,
                    });
};