import {Entity} from "../shared/Entity";

export interface ScoringSetScore extends Entity {
    points: number;
    position: number;
    scoringSetId: number;
}