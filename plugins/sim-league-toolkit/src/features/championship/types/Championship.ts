import {ChampionshipType} from '../../../enums/generated/ChampionshipType';

export interface Championship {
    id: number;
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
