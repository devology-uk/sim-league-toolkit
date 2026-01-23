import {Entity} from './Entity';

export interface ChampionshipEventFormData extends Entity {
    championshipId: number;
    description: string;
    isActive: boolean;
    name: string;
    ruleSetId: number;
    startDateTime: string;
    trackId: number;
    trackLayoutId?: number;
}