const championshipRootEndpoint = '/championship';
const championshipsRootEndpoint = '/championships';
const championshipClassRootEndpoint = '/championship-class';
const championshipEventRootEndpoint = '/championship-event';

export const championshipDeleteEndpoint = (championshipId: number) => `${championshipRootEndpoint}/${championshipId}`;

export const championshipPostEndpoint = () => championshipRootEndpoint;

export const championshipPutEndpoint = (championshipId: number) => `${championshipRootEndpoint}/${championshipId}`;

export const championshipsGetEndpoint = () => championshipsRootEndpoint;


export const championshipClassesGetEndpoint = (championshipId: number): string => `${championshipRootEndpoint}/${championshipId}/classes`;

export const championshipClassPostEndpoint = (championshipId: number): string => `${championshipRootEndpoint}/${championshipId}/classes`;

export const championshipClassDeleteEndpoint = (eventClassId: number) => `${championshipClassRootEndpoint}/${eventClassId}`;


export const championshipEventEndpoint = (championshipEventId: number) => `${championshipEventRootEndpoint}/${championshipEventId}`;

export const championshipEventsEndpoint = (championshipId: number): string => `${championshipRootEndpoint}/${championshipId}/events`;
