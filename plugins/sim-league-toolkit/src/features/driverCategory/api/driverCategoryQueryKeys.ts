export const driverCategoryQueryKeys = {
    all: ['driverCategories'] as const,
    single: (id: number) => ['driverCategories', id],
}
