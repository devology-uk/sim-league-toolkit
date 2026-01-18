import {getApiPath} from '../shared/apiRoutes';

export const serverDeleteRoute = (serverId: number): any => {
    return getApiPath(`server/${serverId}`);
}

export const serverGetRoute = (serverId: number): any => {
    return getApiPath(`server/${serverId}`);
}

export const serverPostRoute = () => {
    return getApiPath('server');
}

export const serversGetRoute = (): any => {
    return getApiPath('server');
}

export const serverSettingsGetRoute = (serverId: number): any => {
    return getApiPath(`server/${serverId}.settings`);
}

export const serverSettingsPostRoute = (): any => {
    return getApiPath('server/settings');
}