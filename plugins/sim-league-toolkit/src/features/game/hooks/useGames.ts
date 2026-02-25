import {useQuery} from '@tanstack/react-query';
import {gameQueryKeys} from '../api/gameQueryKeys';
import {gameApi} from '../api/gameApi';

export const useGames = () => {
    return useQuery({
                        queryKey: gameQueryKeys.all,
                        queryFn: gameApi.list,
                    });
};