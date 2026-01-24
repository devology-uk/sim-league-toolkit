import {getApiPath} from './apiRoutes';

export const driverCategoriesGetRoute = () => {
    return getApiPath('driver-category');
}

export const eventClassDeleteRoute = (eventClassId: number) => {
    return getApiPath(`event-class/${eventClassId}`);
}

export const eventClassesGetRoute = (): string => {
    return getApiPath('event-class');
};

export const eventClassPostRoute = (): string => {
    return getApiPath('event-class');
};

export const eventClassGetRoute = (eventClassId: number) => {
    return getApiPath(`event-class/${eventClassId}`);
}

export const eventClassesForGameGetRoute = (gameId: number): string => {
    return getApiPath(`game/${gameId}/event-classes`);
};