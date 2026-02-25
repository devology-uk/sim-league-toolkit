import {ApiClient} from '../../../api';

import {EventClass, EventClassFormData} from '../';

const eventClassRootEndpoint = '/event-class';
const eventClassesRootEndpoint = '/event-classes';

const endpoints = {
    get: eventClassesRootEndpoint,
    getById: (id: number) => `${eventClassRootEndpoint}/${id}`,
    create: eventClassRootEndpoint,
    update: (id: number) => `${eventClassRootEndpoint}/${id}`,
    delete: (id: number) => `${eventClassRootEndpoint}/${id}`,
}

export const eventClassApi = {
    list: async (): Promise<EventClass[]> => {
        const response = await ApiClient.get<EventClass[]>(endpoints.get);
        if (!response.success) {
            throw new Error('Failed to fetch event classes');
        }
        return response.data ?? [];
    },

    getById: async (id: number): Promise<EventClass> => {
        const response = await ApiClient.get<EventClass>(endpoints.getById(id));
        if (!response.success) {
            throw new Error(`Failed to fetch event class with ${id}`);
        }
        return response.data;
    },

    create: async (data: EventClassFormData): Promise<number> => {
        const response = await ApiClient.post<number>(endpoints.create, data);
        if (!response.success) {
            throw new Error('Failed to create event class');
        }
        return response.data;
    },

    update: async (id: number, data: EventClassFormData): Promise<void> => {
        const response = await ApiClient.put<void>(endpoints.update(id), data);
        if (!response.success) {
            throw new Error(`Failed to update event class with ${id}`);
        }
    },

    delete: async (id: number): Promise<void> => {
        const response = await ApiClient.delete(endpoints.delete(id));
        if (!response.success) {
            throw new Error(`Failed to delete event class with ${id}`);
        }
    },
}
