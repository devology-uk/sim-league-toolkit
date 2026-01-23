import {Entity} from "./Entity";

export interface ScoringSet extends Entity {
    description: string;
    isBuiltIn?: boolean;
    isInUse?: boolean;
    name: string;
    pointsForFastestLap: number;
    pointsForFinishing: number;
    pointsForPole: number;
}