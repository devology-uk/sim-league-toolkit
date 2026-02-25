export const gameQueryKeys = {
        all: ['games'] as const,
        single: (id: number) => ['games', id] as const,
        config: (gameKay: string) => ['gameConfig', gameKay],
} as const;