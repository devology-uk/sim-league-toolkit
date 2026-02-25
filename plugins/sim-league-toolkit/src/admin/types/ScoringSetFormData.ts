import {Entity} from '../../types/Entity';

export interface ScoringSetFormData extends Entity {
    description: string;
    isBuiltIn?: boolean;
    isInUse?: boolean;
    name: string;
    pointsForFastestLap: number;
    pointsForFinishing: number;
    pointsForPole: number;
}