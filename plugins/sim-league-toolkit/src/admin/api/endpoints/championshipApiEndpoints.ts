const championshipRootEndpoint = (): string => {
    return 'championship';
};

const championshipsEndPoint = () => {
    return 'championships';
}

const championshipClassRootEndpoint = (): string => {
    return 'championship-class';
};

const championshipEventRootEndpoint = (): string => {
    return 'championship-event';
};

export const championshipGetEndPoint = (championshipId: number): string => {
    return `${championshipRootEndpoint()}/${championshipId}}`;
};

export const championshipClassesGetEndpoint = (championshipId: number): string => {
    return `${championshipRootEndpoint()}/${championshipId}/classes`;
};

export const championshipClassesPostEndpoint = (championshipId: number): string => {
    return `${championshipRootEndpoint()}/${championshipId}/classes`;
};

export const championshipClassDeleteEndpoint = (eventClassId: number) => {
    return `${championshipClassRootEndpoint()}/${eventClassId}`;
};