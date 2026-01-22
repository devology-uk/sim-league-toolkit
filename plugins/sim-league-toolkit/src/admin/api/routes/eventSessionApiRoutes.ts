import { getApiPath } from '../../components/shared/apiRoutes';

export const eventSessionsGetRoute = (): string => {
    return getApiPath('event-session');
};

export const eventSessionGetRoute = (sessionId: number): string => {
    return getApiPath(`event-session/${sessionId}`);
};

export const eventSessionPostRoute = (): string => {
    return getApiPath('event-session');
};

export const eventSessionPutRoute = (sessionId: number): string => {
    return getApiPath(`event-session/${sessionId}`);
};

export const eventSessionDeleteRoute = (sessionId: number): string => {
    return getApiPath(`event-session/${sessionId}`);
};

export const eventSessionsByEventRefGetRoute = (eventRefId: number): string => {
    return getApiPath(`event-refs/${eventRefId}/sessions`);
};

export const eventSessionsReorderPutRoute = (eventRefId: number): string => {
    return getApiPath(`event-refs/${eventRefId}/sessions/reorder`);
};