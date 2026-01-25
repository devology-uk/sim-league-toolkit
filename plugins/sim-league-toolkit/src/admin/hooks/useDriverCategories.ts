import {useState, useEffect, useCallback} from '@wordpress/element';

import {ApiClient} from '../api/ApiClient';
import {DriverCategory} from '../types/DriverCategory';
import {driverCategoriesGetEndpoint} from '../api/endpoints/eventClassApiEndpoints';

interface UseDriverCategoriesResult {
    driverCategories: DriverCategory[];
    findDriverCategory: (id: number) => DriverCategory;
    isLoading: boolean;
    refresh: () => Promise<void>;
}

export const useGames = (gameId: number, driverCategoryClass: string = ''): UseDriverCategoriesResult => {
    const [driverCategories, setDriverCategories] = useState<DriverCategory[]>([]);
    const [isLoading, setIsLoading] = useState(true);

    const refresh = useCallback(async () => {
        setIsLoading(true);

        const apiResponse = await ApiClient.get<DriverCategory[]>(driverCategoriesGetEndpoint());
        if (apiResponse.success) {
            setDriverCategories(apiResponse.data ?? []);
        }

        setIsLoading(false);
    }, []);

    const findDriverCategory = (id: number): DriverCategory => driverCategories.find((c) => c.id === id);

    useEffect(() => {
        refresh().then(_ => {
        });
    }, [refresh]);

    return {
        driverCategories,
        findDriverCategory,
        isLoading,
        refresh,
    };
};