export const eventSessionRootEndpoint =  '/event-session';

export const eventSessionEndpoint = (sessionId: number): string => {
    return `${eventSessionRootEndpoint}/${sessionId}`;
};

export const eventSessionsByEventRefEndpoint = (eventRefId: number): string => {
    return `event-refs/${eventRefId}/sessions`;
};

export const eventSessionsReorderEndpoint = (eventRefId: number): string => {
    return `${eventSessionsByEventRefEndpoint(eventRefId)}/reorder`;
};