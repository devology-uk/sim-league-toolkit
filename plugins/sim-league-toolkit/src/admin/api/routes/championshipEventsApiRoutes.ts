import {championshipRootEndpoint} from './championshipApiEndpoints';

export const championshipEventEndpoint = (championshipEventId: number) => {
    return `${championshipEventRootEndpoint()}/${championshipEventId}`;
};

export const championshipEventsEndpoint = (championshipId: number): string => {
    return `${championshipRootEndpoint()}/${championshipId}/events`;
};

export const championshipEventRootEndpoint = (): string => {
    return `championship-event`;
};