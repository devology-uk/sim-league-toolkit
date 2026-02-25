export const eventClassQueryKeys = {
    all: ['eventClasses'] as const,
    single: (id: number) => ['eventClasses', id],
}
