import {ApiClient} from '../../../api';
import {StandaloneEvent} from '../types/StandaloneEvent';
import {StandaloneEventFormData} from '../types/StandaloneEventFormData';

const standaloneEventsRoot = '/standalone-events';
const standaloneEventRoot = '/standalone-event';

const endpoints = {
    list: standaloneEventsRoot,
    getById: (id: number) => `${standaloneEventRoot}/${id}`,
    create: standaloneEventRoot,
    update: (id: number) => `${standaloneEventRoot}/${id}`,
    delete: (id: number) => `${standaloneEventRoot}/${id}`,
};

export const standaloneEventApi = {
    list: async (): Promise<StandaloneEvent[]> => {
        const response = await ApiClient.get<StandaloneEvent[]>(endpoints.list);
        if (!response.success) {
            throw new Error('Failed to fetch standalone events');
        }
        return response.data ?? [];
    },

    getById: async (id: number): Promise<StandaloneEvent> => {
        const response = await ApiClient.get<StandaloneEvent>(endpoints.getById(id));
        if (!response.success) {
            throw new Error(`Failed to fetch standalone event with id ${id}`);
        }
        return response.data;
    },

    create: async (data: StandaloneEventFormData): Promise<number> => {
        const response = await ApiClient.post<number>(endpoints.create, data);
        if (!response.success) {
            throw new Error('Failed to create standalone event');
        }
        return response.data;
    },

    update: async (id: number, data: StandaloneEventFormData): Promise<void> => {
        const response = await ApiClient.put<void>(endpoints.update(id), data);
        if (!response.success) {
            throw new Error(`Failed to update standalone event with id ${id}`);
        }
    },

    delete: async (id: number): Promise<void> => {
        const response = await ApiClient.delete(endpoints.delete(id));
        if (!response.success) {
            throw new Error(`Failed to delete standalone event with id ${id}`);
        }
    },
};
