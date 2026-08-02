const trophy = 'trophy';

export const trophyQueryKeys = {
    standaloneEventPreview: (standaloneEventId: number) => [trophy, 'standaloneEvent', standaloneEventId, 'preview'] as const,
    championshipEventPreview: (championshipEventId: number) => [trophy, 'championshipEvent', championshipEventId, 'preview'] as const,
    championshipPreview: (championshipId: number) => [trophy, 'championship', championshipId, 'preview'] as const,
};
