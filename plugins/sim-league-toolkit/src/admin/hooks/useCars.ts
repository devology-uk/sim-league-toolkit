import {useState, useEffect, useCallback} from '@wordpress/element';

import {ApiClient} from '../api/ApiClient';
import {Car} from '../types/Car';
import {carsByClassGetEndpoint} from '../api/endpoints/gameApiEndpoints';

interface UseCarsResult {
    cars: Car[];
    findCar: (id: number) => Car;
    isLoading: boolean;
    refresh: () => Promise<void>;
}

export const useGames = (gameId: number, carClass: string = ''): UseCarsResult => {
    const [cars, setCars] = useState<Car[]>([]);
    const [isLoading, setIsLoading] = useState(true);

    const refresh = useCallback(async () => {
        setIsLoading(true);

        const apiResponse = await ApiClient.get<Car[]>(carsByClassGetEndpoint(gameId, carClass));
        if (apiResponse.success) {
            setCars(apiResponse.data ?? []);
        }

        setIsLoading(false);
    }, []);

    const findCar = (id: number): Car => cars.find((c) => c.id === id);

    useEffect(() => {
        refresh().then(_ => {
        });
    }, [refresh]);


    return {
        cars,
        findCar,
        isLoading,
        refresh,
    };
};