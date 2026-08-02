const championshipSessionResult = 'championship-session-result';

export const championshipSessionResultQueryKeys = {
    byEventSession: (eventSessionId: number) => [championshipSessionResult, 'eventSession', eventSessionId] as const,
    penalties: (resultId: number) => [championshipSessionResult, resultId, 'penalties'] as const,
};
