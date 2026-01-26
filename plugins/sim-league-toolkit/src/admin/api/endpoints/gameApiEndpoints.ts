
const gameRootEndpoint = 'game';
const gamesRootEndpoint = 'games';

export const carClassesGetEndpoint = (gameId: number): string => {
    return `${gameRootEndpoint}/${gameId}/car-classes`;
}

export const carsByClassGetEndpoint = (gameId: number, carClass: string): string => {
    return `${gameRootEndpoint}/${gameId}/cars/${carClass}`;
}

export const gameGetApiEndpoint = (gameId: number): any => {
    return `${gameRootEndpoint}/${gameId}`;
}

export const gamesGetEndpoint = (): string => {
    return gamesRootEndpoint;
}

export const platformsGetEndpoint = (gameId: number): string => {
    return `${gameRootEndpoint}/${gameId}/platforms`;
}

export const tracksGetEndpoint = (gameId: number): string => {
    return `${gameRootEndpoint}/${gameId}/tracks`;
}

export const trackLayoutsGetEndpoint = (trackId: number): string => {
    return `${gameRootEndpoint}/tracks/${trackId}`;
}