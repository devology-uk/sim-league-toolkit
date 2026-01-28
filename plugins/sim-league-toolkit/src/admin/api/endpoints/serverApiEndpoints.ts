const serverRootEndpoint = '/server';
const serversRootEndpoint = '/servers';
const serverSettingRootEndpoint = '/server-setting';

export const serverDeleteEndpoint = (serverId: number): string => {
    return `${serverRootEndpoint}/${serverId}`;
};

export const serversGetEndpoint = () => {
    return serversRootEndpoint;
};

export const serverPostEndpoint = () => {
    return serverRootEndpoint;
};

export const serverPutEndpoint = (serverId: number): string => {
    return `${serverRootEndpoint}/${serverId}`;
};

export const serverSettingDeleteEndpoint = (serverSettingId: number): string => {
    return `${serverSettingRootEndpoint}/${serverSettingId}`;
};

export const serverSettingsGetEndpoint = (serverId: number): string => {
    return `${serverRootEndpoint}/${serverId}/settings`;
};

export const serverSettingPostEndpoint = (serverId: number): string => {
    return `${serverRootEndpoint}/${serverId}/settings`;
};

export const serverSettingPutEndpoint = (serverSettingId: number): string => {
    return `${serverSettingRootEndpoint}/${serverSettingId}`;
};
