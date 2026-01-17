export const championshipClassesGetRoute = (championshipId: number): string => {
    return getApiPath(`championship/${championshipId}/classes`);
};

export const championshipClassesPostRoute = () => {
    return getApiPath('championship/classes');
};

export const championshipEventsGetRoute = (championshipId: number): string => {
    return getApiPath(`championship/${championshipId}/events`);
};

export const championshipEventPostRoute = (): string => {
    return getApiPath(`championship-event`);
};


export const championshipPostRoute = (): string => {
    return getApiPath(`championship`);
};

export const championshipClassDeleteRoute = (championshipId: number, eventClassId: number) => {
    return getApiPath(`championship/${championshipId}/classes/${eventClassId}`);
};

export const eventClassesGetRoute = (): string => {
    return getApiPath('event-class');
};

export const eventClassesForGameGetRoute = (gameId: number): string => {
    return getApiPath(`game/${gameId}/event-classes`);
};

export const gameGetRoute = (gameId: number): any => {
    return getApiPath(`game/${gameId}`);
};

const getApiPath = (relativePath: string): string => {
    if (relativePath.startsWith('/')) {
        relativePath = relativePath.substring(1);
    }

    return `/sltk/v1/${relativePath}`;
};

