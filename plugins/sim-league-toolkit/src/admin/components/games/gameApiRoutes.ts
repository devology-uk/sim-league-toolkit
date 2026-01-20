import {getApiPath} from '../shared/apiRoutes';

export const carClassesGetRoute = (gameId: number): string => {
    return getApiPath(`game/${gameId}/car-classes`);
}

export const carsByClassGetRoute = (gameId: number, carClas: string): string => {
    return getApiPath(`game/${gameId}/cars/${carClas}`);
}

export const gameGetRoute = (gameId: number): string => {
    return getApiPath(`game/${gameId}`);
};

export const gamesGetRoute = (): string => {
    return getApiPath('game');
}

export const platformsGetRoute = (gameId: number) => {
    return getApiPath(`game/${gameId}/platforms`);
}

export const trackGetRoute = (trackId: number): string => {
    return getApiPath(`game/track/${trackId}`);
};

export const tracksGetRoute = (gameId: number) => {
    return getApiPath(`game/${gameId}/tracks`);
}