import {ApiClient} from '../../../api';

import {DriverCategory} from '../';

const driverCategoryRootEndpoint = '/driver-category';

const endpoints = {
    get: driverCategoryRootEndpoint,
    getById: (id: number) => `${driverCategoryRootEndpoint}/${id}`,
}

export const driverCategoryApi = {
    list: async (): Promise<DriverCategory[]> => {
        const response = await ApiClient.get<DriverCategory[]>(endpoints.get);
        if (!response.success) {
            throw new Error('Failed to fetch driver categories');
        }
        return response.data ?? [];
    },

    getById: async (id: number): Promise<DriverCategory> => {
        const response = await ApiClient.get<DriverCategory>(endpoints.getById(id));
        if (!response.success) {
            throw new Error(`Failed to fetch driver category with ${id}`);
        }
        return response.data;
    },
}
