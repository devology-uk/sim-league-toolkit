export const championshipClassesGetRoute = (championshipId: number): string => {
    return getApiPath(`championship/${championshipId}/classes`)
}

export const championshipClassesPostRoute = () => {
    return getApiPath('championship/classes');
}

export const championshipClassDeleteRoute = (championshipId: number, eventClassId: number) => {
    return getApiPath(`championship/${championshipId}/classes/${eventClassId}`);
}

export const eventClassesGetRoute = (): string => {
    return getApiPath('event-class');
}

export const eventClassesForGameGetRoute = (gameId: number): string => {
    return getApiPath(`game/${gameId}/event-classes`);
}

const getApiPath = (relativePath: string): string => {
    if (relativePath.startsWith('/')) {
        relativePath = relativePath.substring(1);
    }

    return `/sltk/v1/${relativePath}`;
}

