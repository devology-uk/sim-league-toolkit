import {useState, useEffect, useCallback} from '@wordpress/element';

import {ApiClient} from '../api/ApiClient';
import {carClassesGetEndpoint} from '../api/endpoints/gameApiEndpoints';

interface UseCarClassesResult {
    carClasses: string[];
    isLoading: boolean;
    refresh: () => Promise<void>;
}

export const useCarClasses = (gameId: number): UseCarClassesResult => {
    const [carClasses, setCarClasses] = useState<string[]>([]);
    const [isLoading, setIsLoading] = useState(true);

    const refresh = useCallback(async () => {
        setIsLoading(true);

        const apiResponse = await ApiClient.get<string[]>(carClassesGetEndpoint(gameId));
        if (apiResponse.success) {
            setCarClasses(apiResponse.data ?? []);
        }

        setIsLoading(false);
    }, []);

    useEffect(() => {
        refresh().then(_ => {
        });
    }, [refresh]);

    return {
        carClasses: carClasses,
        isLoading,
        refresh,
    };
};