const championship = 'championship';
const championshipEvent = 'championship-event';

export const championshipQueryKeys = {
    all: [championship] as const,
    single: (id: number) => [championship, id] as const,
    classes: (championshipId: number) => [championship, championshipId, 'classes'] as const,
    availableClasses: (championshipId: number) => [championship, championshipId, 'classes', 'available'] as const,
    events: (championshipId: number) => [championship, championshipId, 'events'] as const,
    event: (id: number) => [championshipEvent, id] as const,
};
