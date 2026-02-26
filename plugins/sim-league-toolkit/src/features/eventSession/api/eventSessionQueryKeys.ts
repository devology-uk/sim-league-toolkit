const eventSession = 'event-session';

export const eventSessionQueryKeys = {
    all: [eventSession] as const,
    single: (id: number) => [eventSession, id] as const,
    byEventRef: (eventRefId: number) => [eventSession, 'eventRef', eventRefId] as const,
};
