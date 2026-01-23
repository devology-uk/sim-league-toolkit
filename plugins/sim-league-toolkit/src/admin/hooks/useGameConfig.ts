import {useState, useEffect} from '@wordpress/element';

import {ApiClient} from '../api/ApiClient';
import {GameConfig} from '../types/GameConfig';
import {gameConfigGetEndPoint} from '../api/routes/gameConfigApiEndpoints';

interface UseGameConfigResult {
    config: GameConfig | null;
    isLoading: boolean;
}

export const useGameConfig = (gameKey: string | null): UseGameConfigResult => {
    const [config, setConfig] = useState<GameConfig | null>(null);
    const [isLoading, setIsLoading] = useState(false);

    useEffect(() => {
        if (!gameKey) {
            setConfig(null);
            return;
        }

        const loadConfig = async () => {
            setIsLoading(true);

            const response = await ApiClient.get<GameConfig>(gameConfigGetEndPoint(gameKey));
            if (response.success) {
                setConfig(response.data);
            } else {
                setConfig(null);
            }
            setIsLoading(false);
        };

        loadConfig();
    }, [gameKey]);

    return {config, isLoading: isLoading};
};