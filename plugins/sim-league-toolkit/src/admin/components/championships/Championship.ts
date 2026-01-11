import {Entity} from "../shared/Entity";

export interface Championship extends Entity {
    allowEntryChange: boolean;
    bannerImageUrl: string;
    description: string;
    entryChangeLimit: number;
    game?: string;
    gameId: number;
    isActive: boolean;
    isTrackMasterChampionship: boolean;
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