import {useState, useCallback, useEffect} from '@wordpress/element';

import {ApiClient} from '../api/ApiClient';
import {EventClass} from '../types/EventClass';
import {championshipClassesGetAvailableEndpoint} from '../api/endpoints/championshipApiEndpoints';

interface UseAvailableEventClassesResult {
    availableEventClasses: EventClass[];
    isLoading: boolean;
    refresh: () => Promise<void>;
}

export const useAvailableEventClasses = (championshipId: number):UseAvailableEventClassesResult => {
    const [availableEventClasses, setAvailableEventClasses] = useState<EventClass[]>([]);
    const [isLoading, setIsLoading] = useState(false);

    const refresh = useCallback(async () => {
        setIsLoading(true);

        const apiResponse = await ApiClient.get<EventClass[]>(championshipClassesGetAvailableEndpoint(championshipId));

        if (apiResponse.success) {
            setAvailableEventClasses(apiResponse.data ?? []);
        }

        setIsLoading(false);
    }, [championshipId]);

    useEffect(() => {
        refresh().then(_ => {
        });
    }, [refresh]);

    return {
        availableEventClasses,
        isLoading,
        refresh,
    };
}