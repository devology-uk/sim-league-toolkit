import {Entity} from './Entity';

export interface ResultPenalty extends Entity {
    reason: string;
    penaltySeconds: number | null;
}
