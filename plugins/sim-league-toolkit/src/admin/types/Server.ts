import {Entity} from '../../types/Entity';

export interface Server extends Entity {
    game?: string;
    gameId: number;
    gameKey?: string;
    name: string;
    isHostedServer: boolean;
    platform?: string;
    platformId: number;
}

