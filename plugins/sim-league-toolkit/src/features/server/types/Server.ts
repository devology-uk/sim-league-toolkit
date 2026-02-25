export interface Server {
    id?: number;
    game?: string;
    gameId: number;
    gameKey?: string;
    isHostedServer: boolean;
    name: string;
    platform?: string;
    platformId: number;
}
