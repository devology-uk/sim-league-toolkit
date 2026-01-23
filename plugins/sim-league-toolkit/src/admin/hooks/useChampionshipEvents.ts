import {useState, useEffect, useCallback} from '@wordpress/element';

import {ApiClient} from '../api/ApiClient';
import {ChampionshipEvent} from '../types/ChampionshipEvent';
import {ChampionshipEventFormData} from '../types/ChampionshipEventFormData';
import {CreateResponse} from '../types/CreateResponse';

interface UseChampionshipEventsResult {
    createChampionshipEvent: (data: ChampionshipEventFormData) => Promise<number | null>;
    deleteChampionshipEvent: (id: number) => Promise<boolean>;
    isLoading: boolean;
    refresh: () => Promise<void>;
    events: ChampionshipEvent[];
    updateChampionshipEvent: (id: number, data: ChampionshipEventFormData) => Promise<boolean>;
}

export const useChampionshipEvents = (championshipId: number): UseChampionshipEventsResult => {
    const [events, setEvents] = useState<ChampionshipEvent[]>([]);
    const [isLoading, setIsLoading] = useState(true);

    const refresh = useCallback(async () => {
        if(!championshipId) {
            setEvents([]);
            return;
        }

        setIsLoading(true);

        const response = await ApiClient.get<ChampionshipEvent[]>


    })
}