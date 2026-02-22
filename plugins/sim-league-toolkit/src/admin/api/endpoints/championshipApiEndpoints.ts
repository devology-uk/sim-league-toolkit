const championshipRootEndpoint = '/championship';
const championshipsRootEndpoint = '/championships';
const championshipEventRootEndpoint = '/championship-event';

export const championshipDeleteEndpoint = (championshipId: number) => `${championshipRootEndpoint}/${championshipId}`;

export const championshipPostEndpoint = () => championshipRootEndpoint;

export const championshipPutEndpoint = (championshipId: number) => `${championshipRootEndpoint}/${championshipId}`;

export const championshipsGetEndpoint = () => championshipsRootEndpoint;


export const championshipClassesGetEndpoint = (championshipId: number): string => `${championshipRootEndpoint}/${championshipId}/classes`;

export const championshipClassesGetAvailableEndpoint = (championshipId: number): string => `${championshipRootEndpoint}/${championshipId}/classes/available`;

export const championshipClassPostEndpoint = (championshipId: number): string => `${championshipRootEndpoint}/${championshipId}/classes`;

export const championshipClassDeleteEndpoint = (championshipId: number, eventClassId: number) => `${championshipRootEndpoint}/${championshipId}/classes/${eventClassId}`;


export const championshipEventEndpoint = (championshipEventId: number) => `${championshipEventRootEndpoint}/${championshipEventId}`;

export const championshipEventsEndpoint = (championshipId: number): string => `${championshipRootEndpoint}/${championshipId}/events`;
