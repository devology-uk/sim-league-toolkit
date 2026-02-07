import {useState, useEffect, useCallback} from '@wordpress/element';

import {ApiClient} from '../api/ApiClient';
import {DriverCategory} from '../types/DriverCategory';

import {driverCategoryGetEndpoint} from '../api/endpoints/driverCategoryGetEndpoint';

interface UseDriverCategoriesResult {
    driverCategories: DriverCategory[];
    findDriverCategory: (id: number) => DriverCategory;
    isLoading: boolean;
    refresh: () => Promise<void>;
}

export const useDriverCategories = (): UseDriverCategoriesResult => {
    const [driverCategories, setDriverCategories] = useState<DriverCategory[]>([]);
    const [isLoading, setIsLoading] = useState(true);

    const refresh = useCallback(async () => {
        setIsLoading(true);

        const apiResponse = await ApiClient.get<DriverCategory[]>(driverCategoryGetEndpoint());
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