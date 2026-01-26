import {useState, useEffect, useCallback} from '@wordpress/element';

import {ApiClient} from '../api/ApiClient';
import {Track} from '../types/Track';
import {tracksGetEndpoint} from '../api/endpoints/gameApiEndpoints';

interface UseTracksResult {
    findTrack: (id: number) => Track;
    isLoading: boolean;
    refresh: () => Promise<void>;
    tracks: Track[];
}

export const useTracks = (gameId: number): UseTracksResult => {
    const [tracks, setTracks] = useState<Track[]>([]);
    const [isLoading, setIsLoading] = useState(true);

    const refresh = useCallback(async () => {
        setIsLoading(true);

        const apiResponse = await ApiClient.get<Track[]>(tracksGetEndpoint(gameId));
        if (apiResponse.success) {
            setTracks(apiResponse.data ?? []);
        }

        setIsLoading(false);
    }, []);

    const findTrack = (id: number): Track => tracks.find((t) => t.id === id);

    useEffect(() => {
        refresh().then(_ => {
        });
    }, [refresh]);

    return {
        findTrack,
        isLoading,
        refresh,
        tracks: tracks,
    };
};