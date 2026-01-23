import {getApiPath} from './apiRoutes';

export const championshipRootEndpoint = (): string => {
    return 'championship';
};

export const championshipDeleteRoute = (championshipId: number): string => {
    return getApiPath(`${championshipRootEndpoint()}/${championshipId}}`);
};

export const championshipGetRoute = (championshipId: number): string => {
    return getApiPath(`${championshipRootEndpoint()}/${championshipId}`);
};

export const championshipPostRoute = (): string => {
    return getApiPath(`${championshipRootEndpoint()}`);
};

export const championshipsGetRoute = (): string => {
    return getApiPath(`${championshipRootEndpoint()}`);
};

export const championshipClassesGetRoute = (championshipId: number): string => {
    return getApiPath(`${championshipRootEndpoint()}/${championshipId}/classes`);
};

export const championshipClassesPostRoute = () => {
    return getApiPath(`${championshipRootEndpoint}/classes`);
};

export const championshipClassDeleteRoute = (championshipId: number, eventClassId: number) => {
    return getApiPath(`${championshipRootEndpoint()}/${championshipId}/classes/${eventClassId}`);
};