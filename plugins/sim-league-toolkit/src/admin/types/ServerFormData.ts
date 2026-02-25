import {Entity} from '../../types/Entity';

export interface ServerFormData extends Entity {
    gameId: number;
    gameKey?: string;
    name: string;
    isHostedServer: boolean;
    platformId: number;
}