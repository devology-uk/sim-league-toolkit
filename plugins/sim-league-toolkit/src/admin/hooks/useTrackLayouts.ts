import {useState, useEffect, useCallback} from '@wordpress/element';

import {ApiClient} from '../api/ApiClient';
import {TrackLayout} from '../types/TrackLayout';
import {trackLayoutsGetEndpoint} from '../api/endpoints/gameApiEndpoints';

interface UseTrackLayoutsResult {
    findTrackLayout: (id: number) => TrackLayout;
    isLoading: boolean;
    refresh: () => Promise<void>;
    trackLayouts: TrackLayout[];
}

export const useTrackLayouts = (trackId: number): UseTrackLayoutsResult => {
    const [trackLayouts, setTrackLayouts] = useState<TrackLayout[]>([]);
    const [isLoading, setIsLoading] = useState(true);

    const refresh = useCallback(async () => {
        setIsLoading(true);

        const apiResponse = await ApiClient.get<TrackLayout[]>(trackLayoutsGetEndpoint(trackId));
        if (apiResponse.success) {
            setTrackLayouts(apiResponse.data ?? []);
        }

        setIsLoading(false);
    }, [trackId]);

    const findTrackLayout = (id: number): TrackLayout => trackLayouts.find((t) => t.id === id);

    useEffect(() => {
        refresh().then(_ => {
        });
    }, [refresh]);

    return {
        findTrackLayout,
        isLoading,
        refresh,
        trackLayouts,
    };
};