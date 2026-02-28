const standaloneEvent = 'standaloneEvent';

export const standaloneEventQueryKeys = {
    all: [standaloneEvent] as const,
    single: (id: number) => [standaloneEvent, id] as const,
};
