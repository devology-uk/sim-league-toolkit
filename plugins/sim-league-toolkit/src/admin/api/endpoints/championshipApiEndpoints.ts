const championshipRootEndpoint = 'championship';

const championshipsRootEndpoint = 'championships';

const championshipClassRootEndpoint = 'championship-class';

const championshipEventRootEndpoint = 'championship-event';

export const championshipGetEndPoint = (championshipId: number): string => `${championshipRootEndpoint}/${championshipId}}`;

export const championshipClassesGetEndpoint = (championshipId: number): string => `${championshipRootEndpoint}/${championshipId}/classes`;

export const championshipClassesPostEndpoint = (championshipId: number): string => `${championshipRootEndpoint}/${championshipId}/classes`;

export const championshipClassDeleteEndpoint = (eventClassId: number) => `${championshipClassRootEndpoint}/${eventClassId}`;

export const championshipEventEndpoint = (championshipEventId: number) => `${championshipEventRootEndpoint}/${championshipEventId}`;

export const championshipEventsEndpoint = (championshipId: number): string => `${championshipRootEndpoint}/${championshipId}/events`;

export const championshipsGetEndpoint = () => championshipsRootEndpoint;
