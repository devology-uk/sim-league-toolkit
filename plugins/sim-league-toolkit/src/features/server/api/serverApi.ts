import {ApiClient} from '../../../api';

import {Server, ServerFormData, ServerSetting, ServerSettingFormData} from '../';

const serverRootEndpoint = '/server';
const serversRootEndpoint = '/servers';
const serverSettingRootEndpoint = '/server-setting';

const endpoints = {
    get: serversRootEndpoint,
    getById: (id: number) => `${serverRootEndpoint}/${id}`,
    create: serverRootEndpoint,
    update: (id: number) => `${serverRootEndpoint}/${id}`,
    delete: (id: number) => `${serverRootEndpoint}/${id}`,
    getSettings: (serverId: number) => `${serverRootEndpoint}/${serverId}/settings`,
    getSettingById: (id: number) => `${serverSettingRootEndpoint}/${id}`,
    createSetting: (serverId: number) => `${serverRootEndpoint}/${serverId}/settings`,
    updateSetting: (id: number) => `${serverSettingRootEndpoint}/${id}`,
}

export const serverApi = {
    list: async (): Promise<Server[]> => {
        const response = await ApiClient.get<Server[]>(endpoints.get);
        if (!response.success) {
            throw new Error('Failed to fetch servers');
        }
        return response.data ?? [];
    },

    getById: async (id: number): Promise<Server> => {
        const response = await ApiClient.get<Server>(endpoints.getById(id));
        if (!response.success) {
            throw new Error(`Failed to fetch server with ${id}`);
        }
        return response.data;
    },

    create: async (data: ServerFormData): Promise<number> => {
        const response = await ApiClient.post<number>(endpoints.create, data);
        if (!response.success) {
            throw new Error('Failed to create server');
        }
        return response.data;
    },

    update: async (id: number, data: ServerFormData): Promise<void> => {
        const response = await ApiClient.put<void>(endpoints.update(id), data);
        if (!response.success) {
            throw new Error(`Failed to update server with ${id}`);
        }
    },

    delete: async (id: number): Promise<void> => {
        const response = await ApiClient.delete(endpoints.delete(id));
        if (!response.success) {
            throw new Error(`Failed to delete server with ${id}`);
        }
    },

    listSettings: async (serverId: number): Promise<ServerSetting[]> => {
        const response = await ApiClient.get<ServerSetting[]>(endpoints.getSettings(serverId));
        if (!response.success) {
            throw new Error(`Failed to fetch settings for server with ${serverId}`);
        }
        return response.data ?? [];
    },

    getSettingById: async (id: number): Promise<ServerSetting> => {
        const response = await ApiClient.get<ServerSetting>(endpoints.getSettingById(id));
        if (!response.success) {
            throw new Error(`Failed to fetch server setting with ${id}`);
        }
        return response.data;
    },

    createSetting: async (serverId: number, data: ServerSettingFormData): Promise<number> => {
        const response = await ApiClient.post<number>(endpoints.createSetting(serverId), data);
        if (!response.success) {
            throw new Error(`Failed to create setting for server with ${serverId}`);
        }
        return response.data;
    },

    updateSetting: async (id: number, data: ServerSettingFormData): Promise<void> => {
        const response = await ApiClient.put<void>(endpoints.updateSetting(id), data);
        if (!response.success) {
            throw new Error(`Failed to update server setting with ${id}`);
        }
    },
}
