export const ruleSetQueryKeys = {
    all: ['ruleSets'] as const,
    single: (id: number) => ['ruleSets', id],
    rules: (ruleSetId: number) => ['ruleSets', ruleSetId, 'rules'],
    rule: (id: number) => ['ruleSets', 'rules', id],
}