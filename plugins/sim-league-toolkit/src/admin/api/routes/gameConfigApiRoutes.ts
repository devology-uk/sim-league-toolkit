import {getApiPath} from './apiRoutes';

export const gameConfigsGetRoute = (): string => {
    return getApiPath('game-config');
};

export const gameConfigGetRoute = (gameId: string): string => {
    return getApiPath(`game-config/${gameId}`);
};