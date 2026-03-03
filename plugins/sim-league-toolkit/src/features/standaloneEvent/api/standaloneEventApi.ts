import {ApiClient} from '../../../api';
import {StandaloneEvent} from '../types/StandaloneEvent';
import {StandaloneEventFormData} from '../types/StandaloneEventFormData';
import {StandaloneEventEntry} from '../types/StandaloneEventEntry';
import {StandaloneEventEntryFormData} from '../types/StandaloneEventEntryFormData';
import {StandaloneEventClass} from '../types/StandaloneEventClass';
import {StandaloneEventClassFormData} from '../types/StandaloneEventClassFormData';
import {EventClass} from '../../eventClass';

const standaloneEventsRoot = '/standalone-events';
const standaloneEventRoot = '/standalone-event';
const standaloneEventEntryRoot = '/standalone-event-entry';

const endpoints = {
    list: standaloneEventsRoot,
    getById: (id: number) => `${standaloneEventRoot}/${id}`,
    create: standaloneEventRoot,
    update: (id: number) => `${standaloneEventRoot}/${id}`,
    delete: (id: number) => `${standaloneEventRoot}/${id}`,
    listEntries: (standaloneEventId: number) => `${standaloneEventRoot}/${standaloneEventId}/entries`,
    createEntry: (standaloneEventId: number) => `${standaloneEventRoot}/${standaloneEventId}/entries`,
    deleteEntry: (id: number) => `${standaloneEventEntryRoot}/${id}`,
    listClasses: (id: number) => `${standaloneEventRoot}/${id}/classes`,
    listAvailableClasses: (id: number) => `${standaloneEventRoot}/${id}/classes/available`,
    createClass: (id: number) => `${standaloneEventRoot}/${id}/classes`,
    deleteClass: (id: number, eventClassId: number) => `${standaloneEventRoot}/${id}/classes/${eventClassId}`,
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

    listEntries: async (standaloneEventId: number): Promise<StandaloneEventEntry[]> => {
        const response = await ApiClient.get<StandaloneEventEntry[]>(endpoints.listEntries(standaloneEventId));
        if (!response.success) {
            throw new Error(`Failed to fetch entries for standalone event with id ${standaloneEventId}`);
        }
        return response.data ?? [];
    },

    createEntry: async (standaloneEventId: number, data: StandaloneEventEntryFormData): Promise<number> => {
        const response = await ApiClient.post<number>(endpoints.createEntry(standaloneEventId), data);
        if (!response.success) {
            throw new Error('Failed to create standalone event entry');
        }
        return response.data;
    },

    deleteEntry: async (id: number): Promise<void> => {
        const response = await ApiClient.delete(endpoints.deleteEntry(id));
        if (!response.success) {
            throw new Error(`Failed to delete standalone event entry with id ${id}`);
        }
    },

    listClasses: async (standaloneEventId: number): Promise<StandaloneEventClass[]> => {
        const response = await ApiClient.get<StandaloneEventClass[]>(endpoints.listClasses(standaloneEventId));
        if (!response.success) {
            throw new Error(`Failed to fetch classes for standalone event with id ${standaloneEventId}`);
        }
        return response.data ?? [];
    },

    listAvailableClasses: async (standaloneEventId: number): Promise<EventClass[]> => {
        const response = await ApiClient.get<EventClass[]>(endpoints.listAvailableClasses(standaloneEventId));
        if (!response.success) {
            throw new Error(`Failed to fetch available classes for standalone event with id ${standaloneEventId}`);
        }
        return response.data ?? [];
    },

    createClass: async (standaloneEventId: number, data: StandaloneEventClassFormData): Promise<void> => {
        const response = await ApiClient.post<void>(endpoints.createClass(standaloneEventId), data);
        if (!response.success) {
            throw new Error('Failed to create standalone event class');
        }
    },

    deleteClass: async (standaloneEventId: number, eventClassId: number): Promise<void> => {
        const response = await ApiClient.delete(endpoints.deleteClass(standaloneEventId, eventClassId));
        if (!response.success) {
            throw new Error(`Failed to delete class ${eventClassId} from standalone event ${standaloneEventId}`);
        }
    },
};
