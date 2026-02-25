export const serverQueryKeys = {
    all: ['servers'] as const,
    single: (id: number) => ['servers', id],
    settings: (serverId: number) => ['servers', serverId, 'settings'],
    setting: (id: number) => ['servers', 'settings', id],
}
