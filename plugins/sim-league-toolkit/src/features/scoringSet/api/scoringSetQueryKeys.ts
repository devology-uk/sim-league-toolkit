export const scoringSetQueryKeys = {
    all: ['scoringSets'] as const,
    single: (id: number) => ['scoringSets', id],
    scores: (scoringSetId: number) => ['scoringSets', scoringSetId, 'scores'],
    score: (id: number) => ['scoringSets', 'scores', id],
}