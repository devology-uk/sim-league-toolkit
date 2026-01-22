import { useState, useEffect } from '@wordpress/element';
import apiFetch from '@wordpress/api-fetch';

import { GameConfig } from '../types/GameConfig';
import { gameConfigGetRoute } from '../api/routes/gameConfigApiRoutes';

interface UseGameConfigResult {
    config: GameConfig | null;
    loading: boolean;
}

export const useGameConfig = (gameId: string | null): UseGameConfigResult => {
    const [config, setConfig] = useState<GameConfig | null>(null);
    const [loading, setLoading] = useState(false);

    useEffect(() => {
        if (!gameId) {
            setConfig(null);
            return;
        }

        const loadConfig = async () => {
            setLoading(true);

            try {
                const data = await apiFetch<GameConfig>({
                                                            path: gameConfigGetRoute(gameId),
                                                            method: 'GET',
                                                        });
                setConfig(data);
            } catch (e) {
                setConfig(null);
            } finally {
                setLoading(false);
            }
        };

        loadConfig();
    }, [gameId]);

    return { config, loading };
};