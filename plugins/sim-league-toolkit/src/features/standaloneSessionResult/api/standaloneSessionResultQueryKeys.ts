const standaloneSessionResult = 'standalone-session-result';

export const standaloneSessionResultQueryKeys = {
    byEventSession: (eventSessionId: number) => [standaloneSessionResult, 'eventSession', eventSessionId] as const,
    penalties: (resultId: number) => [standaloneSessionResult, resultId, 'penalties'] as const,
};
