import {Entity} from './Entity';
import {ChampionshipType} from './generated/ChampionshipType';

export interface Championship extends Entity {
    allowEntryChange: boolean;
    bannerImageUrl: string;
    championshipType: ChampionshipType;
    description: string;
    entryChangeLimit: number;
    game?: string;
    gameId: number;
    isActive: boolean;
    name: string;
    platform?: string;
    platformId: number;
    resultsToDiscard: number;
    ruleSet?: string;
    ruleSetId: number;
    scoringSet?: string;
    scoringSetId: number;
    startDate: Date;
    trackMasterTrack?: string;
    trackMasterTrackId?: number;
    trackMasterTrackLayout?: string;
    trackMasterTrackLayoutId?: number;
    trophiesAwarded: boolean;
}


