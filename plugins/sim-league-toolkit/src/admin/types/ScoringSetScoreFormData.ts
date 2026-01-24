import {Entity} from './Entity';

export interface ScoringSetScoreFormData extends Entity {
    points: number;
    position: number;
    scoringSetId: number;
}