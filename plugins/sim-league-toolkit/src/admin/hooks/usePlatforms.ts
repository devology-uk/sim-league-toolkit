import {useState, useEffect, useCallback} from '@wordpress/element';

import {ApiClient} from '../api/ApiClient';
import {Platform} from '../types/Platform';
import {platformsGetEndpoint} from '../api/endpoints/gameApiEndpoints';

interface UsePlatformsResult {
    platforms: Platform[];
    isLoading: boolean;
    refresh: () => Promise<void>;
}

export const useGames = (gameId: number): UsePlatformsResult => {
    const [platforms, setPlatforms] = useState<Platform[]>([]);
    const [isLoading, setIsLoading] = useState(true);

    const refresh = useCallback(async () => {
        setIsLoading(true);

        const apiResponse = await ApiClient.get<Platform[]>(platformsGetEndpoint(gameId));
        if (apiResponse.success) {
            setPlatforms(apiResponse.data ?? []);
        }

        setIsLoading(false);
    }, []);

    useEffect(() => {
        refresh().then(_ => {
        });
    }, [refresh]);

    return {
        platforms: platforms,
        isLoading,
        refresh,
    };
};