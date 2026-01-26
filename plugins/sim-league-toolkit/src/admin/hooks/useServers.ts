import {__} from '@wordpress/i18n';
import {useState, useEffect, useCallback} from '@wordpress/element';

import {ApiClient} from '../api/ApiClient';
import {CreateResponse} from '../types/CreateResponse';
import {Server} from '../types/Server';
import {ServerFormData} from '../types/ServerFormData';
import {
    serversGetEndpoint,
    serverPostEndpoint,
    serverDeleteEndpoint, serverPutEndpoint
} from '../api/endpoints/serverApiEndpoints';

interface UseServersResult {
    createServer: (data: ServerFormData) => Promise<number | null>;
    deleteServer: (id: number) => Promise<boolean>;
    findServer: (id: number) => Server;
    isLoading: boolean;
    refresh: () => Promise<void>;
    servers: Server[];
    updateServer: (id: number, data: ServerFormData) => Promise<boolean>;
}

export const useServers = (): UseServersResult => {
    const [servers, setServers] = useState<Server[]>([]);
    const [isLoading, setIsLoading] = useState(false);

    const refresh = useCallback(async () => {
        setIsLoading(true);

        const apiResponse = await ApiClient.get<Server[]>(serversGetEndpoint());
        if (apiResponse.success) {
            setServers(apiResponse.data ?? []);
        }
        setIsLoading(false);

    }, []);

    const createServer = useCallback(async (data: ServerFormData): Promise<number | null> => {
        const apiResponse = await ApiClient.post<CreateResponse>(serverPostEndpoint(), data);
        if (apiResponse.success && apiResponse.data) {

            ApiClient.showSuccess(__('Server created successfully', 'sim-league-toolkit'));
            await refresh();
            return apiResponse.data.id;
        }

        return null;

    }, [refresh]);

    const deleteServer = useCallback(async (id: number): Promise<boolean> => {
        const apiResponse = await ApiClient.delete(serverDeleteEndpoint(id));
        if (apiResponse.success) {
            ApiClient.showSuccess(__('Server deleted successfully', 'sim-league-toolkit'));
            await refresh();
        }
        return apiResponse.success;
    }, [refresh]);

    const updateServer = useCallback(async (id: number, data: ServerFormData): Promise<boolean> => {
        const apiResponse = await ApiClient.put(serverPutEndpoint(id), data);
        if (apiResponse.success) {
            ApiClient.showSuccess(__('Server updated successfully', 'sim-league-toolkit'));
            await refresh();
        }
        return apiResponse.success;
    }, [refresh]);

    const findServer = (id: number) => servers.find(s => s.id === id);

    useEffect(() => {
        refresh().then(_ => {
        });
    }, [refresh]);

    return {
        createServer,
        deleteServer,
        findServer,
        servers,
        isLoading,
        refresh,
        updateServer,
    };

};

