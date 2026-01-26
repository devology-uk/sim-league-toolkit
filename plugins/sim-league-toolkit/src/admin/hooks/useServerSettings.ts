import {__} from '@wordpress/i18n';
import {useState, useCallback, useEffect} from '@wordpress/element';

import {ApiClient} from '../api/ApiClient';
import {CreateResponse} from '../types/CreateResponse';
import {ServerSetting} from '../types/ServerSetting';
import {ServerSettingFormData} from '../types/ServerSettingFormData';
import {
    serverSettingsGetEndpoint,
    serverSettingDeleteEndpoint,
    serverSettingPutEndpoint, serverSettingPostEndpoint
} from '../api/endpoints/serverApiEndpoints';

interface UseServerSettingsResult {
    createServerSetting: (serverId: number, data: ServerSettingFormData) => Promise<number>;
    deleteServerSetting: (id: number) => Promise<boolean>;
    findServerSetting: (id: number) => ServerSetting;
    findServerSettingByName: (name: string) => ServerSetting | null;
    isLoading: boolean;
    refresh: () => Promise<void>
    serverSettings: ServerSetting[];
    updateServerSetting: (id: number, data: ServerSettingFormData) => Promise<boolean>;
}

export const useServerSettings = (serverId: number | null) : UseServerSettingsResult => {
    const [serverSettings, setServerSettings] = useState<ServerSetting[]>([]);
    const [isLoading, setIsLoading] = useState(false);

    const refresh = useCallback(async () => {
        if (!serverId) {
            setServerSettings([]);
            return;
        }

        setIsLoading(true);
        const response = await ApiClient.get<ServerSetting[]>(
            serverSettingsGetEndpoint(serverId)
        );

        if (response.success) {
            setServerSettings(response.data ?? []);
        }

        setIsLoading(false);
    }, [serverId]);

    const createServerSetting = useCallback(async (serverId:number, data: ServerSettingFormData): Promise<number> => {
        const response = await ApiClient.post<CreateResponse>(
            serverSettingPostEndpoint(serverId!),
            data
        );

        if (response.success && response.data) {
            ApiClient.showSuccess(__('Server Setting added successfully', 'sim-league-toolkit'));
            await refresh();
            return response.data.id;
        }

        return null;
    }, [serverId, refresh]);

    const deleteServerSetting = useCallback(async (id: number): Promise<boolean> => {
        const response = await ApiClient.delete(serverSettingDeleteEndpoint(id));

        if (response.success) {
            ApiClient.showSuccess(__('Server Setting deleted successfully', 'sim-league-toolkit'));
            await refresh();
        }

        return response.success;
    }, [refresh]);

    const updateServerSetting = useCallback(async (id: number, data: ServerSettingFormData): Promise<boolean> => {
        const response = await ApiClient.put(serverSettingPutEndpoint(id), data);

        if (response.success) {
            ApiClient.showSuccess(__('Server Setting updated successfully', 'sim-league-toolkit'));
            await refresh();
        }

        return response.success;
    }, [refresh]);

    const findServerSetting = (id: number): ServerSetting => serverSettings.find(ss => ss.id === id);
    const findServerSettingByName = (name: string): ServerSetting => serverSettings.find(ss => ss.settingName === name) ?? null;

    useEffect(() => {
        refresh().then(_ => {});
    }, [refresh]);

    return {
        createServerSetting,
        deleteServerSetting,
        findServerSetting,
        findServerSettingByName,
        isLoading,
        refresh,
        serverSettings,
        updateServerSetting,
    };
};