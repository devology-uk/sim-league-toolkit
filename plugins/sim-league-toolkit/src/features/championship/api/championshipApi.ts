import {ApiClient} from '../../../api';

import {Championship, ChampionshipClass, ChampionshipClassFormData, ChampionshipEntry, ChampionshipEntryFormData, ChampionshipEvent, ChampionshipEventFormData, ChampionshipFormData} from '../';

const championshipRoot = '/championship';
const championshipsRoot = '/championships';
const championshipEventRoot = '/championship-event';

const endpoints = {
    list: championshipsRoot,
    getById: (id: number) => `${championshipRoot}/${id}`,
    create: championshipRoot,
    update: (id: number) => `${championshipRoot}/${id}`,
    delete: (id: number) => `${championshipRoot}/${id}`,
    listClasses: (championshipId: number) => `${championshipRoot}/${championshipId}/classes`,
    listAvailableClasses: (championshipId: number) => `${championshipRoot}/${championshipId}/classes/available`,
    createClass: (championshipId: number) => `${championshipRoot}/${championshipId}/classes`,
    deleteClass: (championshipId: number, eventClassId: number) => `${championshipRoot}/${championshipId}/classes/${eventClassId}`,
    listEntries: (championshipId: number) => `${championshipRoot}/${championshipId}/entries`,
    createEntry: (championshipId: number) => `${championshipRoot}/${championshipId}/entries`,
    deleteEntry: (id: number) => `/championship-entry/${id}`,
    listEvents: (championshipId: number) => `${championshipRoot}/${championshipId}/events`,
    createEvent: (championshipId: number) => `${championshipRoot}/${championshipId}/events`,
    updateEvent: (id: number) => `${championshipEventRoot}/${id}`,
    deleteEvent: (id: number) => `${championshipEventRoot}/${id}`,
};

export const championshipApi = {
    list: async (): Promise<Championship[]> => {
        const response = await ApiClient.get<Championship[]>(endpoints.list);
        if (!response.success) {
            throw new Error('Failed to fetch championships');
        }
        return response.data ?? [];
    },

    getById: async (id: number): Promise<Championship> => {
        const response = await ApiClient.get<Championship>(endpoints.getById(id));
        if (!response.success) {
            throw new Error(`Failed to fetch championship with id ${id}`);
        }
        return response.data;
    },

    create: async (data: ChampionshipFormData): Promise<number> => {
        const response = await ApiClient.post<number>(endpoints.create, data);
        if (!response.success) {
            throw new Error('Failed to create championship');
        }
        return response.data;
    },

    update: async (id: number, data: ChampionshipFormData): Promise<void> => {
        const response = await ApiClient.put<void>(endpoints.update(id), data);
        if (!response.success) {
            throw new Error(`Failed to update championship with id ${id}`);
        }
    },

    delete: async (id: number): Promise<void> => {
        const response = await ApiClient.delete(endpoints.delete(id));
        if (!response.success) {
            throw new Error(`Failed to delete championship with id ${id}`);
        }
    },

    listClasses: async (championshipId: number): Promise<ChampionshipClass[]> => {
        const response = await ApiClient.get<ChampionshipClass[]>(endpoints.listClasses(championshipId));
        if (!response.success) {
            throw new Error(`Failed to fetch classes for championship with id ${championshipId}`);
        }
        return response.data ?? [];
    },

    listAvailableClasses: async (championshipId: number): Promise<ChampionshipClass[]> => {
        const response = await ApiClient.get<ChampionshipClass[]>(endpoints.listAvailableClasses(championshipId));
        if (!response.success) {
            throw new Error(`Failed to fetch available classes for championship with id ${championshipId}`);
        }
        return response.data ?? [];
    },

    createClass: async (championshipId: number, data: ChampionshipClassFormData): Promise<number> => {
        const response = await ApiClient.post<number>(endpoints.createClass(championshipId), data);
        if (!response.success) {
            throw new Error(`Failed to create class for championship with id ${championshipId}`);
        }
        return response.data;
    },

    deleteClass: async (championshipId: number, eventClassId: number): Promise<void> => {
        const response = await ApiClient.delete(endpoints.deleteClass(championshipId, eventClassId));
        if (!response.success) {
            throw new Error(`Failed to delete class ${eventClassId} from championship with id ${championshipId}`);
        }
    },

    listEvents: async (championshipId: number): Promise<ChampionshipEvent[]> => {
        const response = await ApiClient.get<ChampionshipEvent[]>(endpoints.listEvents(championshipId));
        if (!response.success) {
            throw new Error(`Failed to fetch events for championship with id ${championshipId}`);
        }
        return response.data ?? [];
    },

    createEvent: async (championshipId: number, data: ChampionshipEventFormData): Promise<number> => {
        const response = await ApiClient.post<number>(endpoints.createEvent(championshipId), data);
        if (!response.success) {
            throw new Error(`Failed to create event for championship with id ${championshipId}`);
        }
        return response.data;
    },

    updateEvent: async (id: number, data: ChampionshipEventFormData): Promise<void> => {
        const response = await ApiClient.put<void>(endpoints.updateEvent(id), data);
        if (!response.success) {
            throw new Error(`Failed to update championship event with id ${id}`);
        }
    },

    deleteEvent: async (id: number): Promise<void> => {
        const response = await ApiClient.delete(endpoints.deleteEvent(id));
        if (!response.success) {
            throw new Error(`Failed to delete championship event with id ${id}`);
        }
    },

    listEntries: async (championshipId: number): Promise<ChampionshipEntry[]> => {
        const response = await ApiClient.get<ChampionshipEntry[]>(endpoints.listEntries(championshipId));
        if (!response.success) {
            throw new Error(`Failed to fetch entries for championship with id ${championshipId}`);
        }
        return response.data ?? [];
    },

    createEntry: async (championshipId: number, data: ChampionshipEntryFormData): Promise<number> => {
        const response = await ApiClient.post<number>(endpoints.createEntry(championshipId), data);
        if (!response.success) {
            throw new Error(`Failed to create entry for championship with id ${championshipId}`);
        }
        return response.data;
    },

    deleteEntry: async (id: number): Promise<void> => {
        const response = await ApiClient.delete(endpoints.deleteEntry(id));
        if (!response.success) {
            throw new Error(`Failed to delete championship entry with id ${id}`);
        }
    },
};
