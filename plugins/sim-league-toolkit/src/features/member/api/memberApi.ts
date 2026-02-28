import {ApiClient} from '../../../api';
import {Member} from '../types/Member';

const endpoints = {
    list: '/members',
};

export const memberApi = {
    list: async (): Promise<Member[]> => {
        const response = await ApiClient.get<Member[]>(endpoints.list);
        if (!response.success) {
            throw new Error('Failed to fetch members');
        }
        return response.data ?? [];
    },
};
