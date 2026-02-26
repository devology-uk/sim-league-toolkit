import {ApiClient} from '../../../api';

import {EventSession, EventSessionFormData} from '../';

const eventRefRoot = '/event-refs';
const eventSessionRoot = '/event-session';

const endpoints = {
    listByEventRef: (eventRefId: number) => `${eventRefRoot}/${eventRefId}/event-sessions`,
    reorder: (eventRefId: number) => `${eventRefRoot}/${eventRefId}/event-sessions/reorder`,
    getById: (id: number) => `${eventSessionRoot}/${id}`,
    create: eventSessionRoot,
    update: (id: number) => `${eventSessionRoot}/${id}`,
    delete: (id: number) => `${eventSessionRoot}/${id}`,
};

export const eventSessionApi = {
    listByEventRefId: async (eventRefId: number): Promise<EventSession[]> => {
        const response = await ApiClient.get<EventSession[]>(endpoints.listByEventRef(eventRefId));
        if (!response.success) {
            throw new Error(`Failed to fetch sessions for event ref ${eventRefId}`);
        }
        return response.data ?? [];
    },

    getById: async (id: number): Promise<EventSession> => {
        const response = await ApiClient.get<EventSession>(endpoints.getById(id));
        if (!response.success) {
            throw new Error(`Failed to fetch event session with id ${id}`);
        }
        return response.data;
    },

    create: async (data: EventSessionFormData): Promise<number> => {
        const response = await ApiClient.post<number>(endpoints.create, data);
        if (!response.success) {
            throw new Error('Failed to create event session');
        }
        return response.data;
    },

    update: async (id: number, data: EventSessionFormData): Promise<void> => {
        const response = await ApiClient.put<void>(endpoints.update(id), data);
        if (!response.success) {
            throw new Error(`Failed to update event session with id ${id}`);
        }
    },

    delete: async (id: number): Promise<void> => {
        const response = await ApiClient.delete(endpoints.delete(id));
        if (!response.success) {
            throw new Error(`Failed to delete event session with id ${id}`);
        }
    },

    reorder: async (eventRefId: number, sessionIds: number[]): Promise<void> => {
        const response = await ApiClient.post<void>(endpoints.reorder(eventRefId), {sessionIds});
        if (!response.success) {
            throw new Error(`Failed to reorder sessions for event ref ${eventRefId}`);
        }
    },
};
