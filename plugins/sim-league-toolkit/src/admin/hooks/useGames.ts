import {useState, useEffect, useCallback} from '@wordpress/element';

import {ApiClient} from '../api/ApiClient';
import {Game} from '../types/Game';
import {gamesGetEndpoint} from '../api/endpoints/gameApiEndpoints';

interface UseGamesResult {
    findGame: (id: number) => Game;
    games: Game[];
    isLoading: boolean;
    refresh: () => Promise<void>;
}

export const useGames = (): UseGamesResult => {
    const [games, setGames] = useState<Game[]>([]);
    const [isLoading, setIsLoading] = useState(true);

    const refresh = useCallback(async () => {
        setIsLoading(true);

        const apiResponse = await ApiClient.get<Game[]>(gamesGetEndpoint());
        if (apiResponse.success) {
            setGames(apiResponse.data ?? []);
        }

        setIsLoading(false);
    }, []);

    const findGame = (id: number) => games.find((g) => g.id === id);

    useEffect(() => {
        refresh().then(_ => {
        });
    }, [refresh]);

    return {
        findGame,
        games,
        isLoading,
        refresh,
    };
};