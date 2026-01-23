import {getApiPath} from './apiRoutes';

export const championshipEventDeleteRoute = (championshipEventId: number) => {
    return getApiPath(`championship-event/${championshipEventId}`);
};

export const championshipEventsGetRoute = (championshipId: number): string => {
    return getApiPath(`championship/${championshipId}/events`);
};

export const championshipEventPostRoute = (): string => {
    return getApiPath(`championship-event`);
};