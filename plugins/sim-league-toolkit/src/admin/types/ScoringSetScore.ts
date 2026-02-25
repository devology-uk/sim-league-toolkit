import {Entity} from '../../types/Entity';

export interface ScoringSetScore extends Entity {
    points: number;
    position: number;
    scoringSetId: number;
}
