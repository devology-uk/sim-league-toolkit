import {getApiPath} from './apiRoutes';

export const championshipRootEndpoint = (): string => {
    return 'championship';
};

export const championshipsEndPoint = () => {
    return 'championships';
}

export const championshipEndPoint = (championshipId: number): string => {
    return `${championshipRootEndpoint()}/${championshipId}}`;
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