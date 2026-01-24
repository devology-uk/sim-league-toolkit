import {getApiPath} from './apiRoutes';

const serverRootEndpoint =() => {
    return 'server';
};

export const serverEndpoint = (serverId: number): any => {
    return `${serverRootEndpoint()}/${serverId}`;
}

export const serversEndpoint = () => {
    return serverRootEndpoint();
}

export const serverSettingsEndpoint = (serverId: number): any => {
    return `${serverRootEndpoint()}/${serverId}/settings`;
}