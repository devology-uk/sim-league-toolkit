const standaloneEvent = 'standaloneEvent';

export const standaloneEventQueryKeys = {
    all: [standaloneEvent] as const,
    single: (id: number) => [standaloneEvent, id] as const,
    entries: (standaloneEventId: number) => [standaloneEvent, standaloneEventId, 'entries'] as const,
    classes: (standaloneEventId: number) => [standaloneEvent, standaloneEventId, 'classes'] as const,
    availableClasses: (standaloneEventId: number) => [standaloneEvent, standaloneEventId, 'classes', 'available'] as const,
};
