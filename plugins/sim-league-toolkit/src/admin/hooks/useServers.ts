import {useState, useEffect, useCallback} from '@wordpress/element';

import {ApiClient} from '../api/ApiClient';
import {CreateResponse} from '../types/CreateResponse';
import {Server} from '../types/Server';
import {
    serverEndpoint,
    serversEndpoint
} from '../api/endpoints/serverApiEndpoints';
import {ServerFormData} from '../types/ServerFormData';

interface UseServersResult {
    createServer: (data: ServerFormData) => Promise<number | null>;
    deleteServer: (id: number) => Promise<boolean>;
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

        const apiResponse = await ApiClient.get<Server[]>(serversEndpoint());
        if (apiResponse.success) {
            setServers(apiResponse.data ?? []);
        }
        setIsLoading(false);

    }, []);

    const createServer = useCallback(async (data: ServerFormData): Promise<number | null> => {
        const apiResponse = await ApiClient.post<CreateResponse>(serversEndpoint(), data);
        await refresh();
        return apiResponse.data.id;
    }, [refresh]);

    const deleteServer = useCallback(async (id: number): Promise<boolean> => {
        const apiResponse = await ApiClient.delete(serverEndpoint(id));
        await refresh();
        return apiResponse.success;
    }, [refresh]);

    const updateServer = useCallback(async (id: number, data: ServerFormData): Promise<boolean> => {
        const apiResponse = await ApiClient.put(serverEndpoint(id), data);
        await refresh();
        return apiResponse.success;
    }, [refresh]);

    useEffect(() => {
        refresh().then(_ => {
        });
    }, [refresh]);

    return {
        createServer,
        deleteServer,
        servers,
        isLoading,
        refresh,
        updateServer,
    };
    
}

