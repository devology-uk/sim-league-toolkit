import {getApiPath} from './apiRoutes';

export const championshipDeleteRoute = (championshipId: number): string => {
    return getApiPath(`championship/${championshipId}}`);
};

export const championshipGetRoute = (championshipId: number): string => {
    return getApiPath(`championship/${championshipId}`);
};

export const championshipPostRoute = (): string => {
    return getApiPath(`championship`);
};

export const championshipsGetRoute = (): string => {
    return getApiPath(`championship`);
};

export const championshipClassesGetRoute = (championshipId: number): string => {
    return getApiPath(`championship/${championshipId}/classes`);
};

export const championshipClassesPostRoute = () => {
    return getApiPath('championship/classes');
};

export const championshipClassDeleteRoute = (championshipId: number, eventClassId: number) => {
    return getApiPath(`championship/${championshipId}/classes/${eventClassId}`);
};