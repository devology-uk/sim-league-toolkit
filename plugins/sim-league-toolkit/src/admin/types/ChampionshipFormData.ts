import {ChampionshipType} from './generated/ChampionshipType';

export interface ChampionshipFormData {
    allowEntryChange: boolean;
    bannerImageUrl: string;
    championshipType: ChampionshipType;
    description: string;
    entryChangeLimit: number;
    gameId: number;
    isActive: boolean;
    name: string;
    platformId: number;
    resultsToDiscard: number;
    ruleSetId: number;
    scoringSetId: number;
    startDate: Date;
    trackMasterTrackId?: number;
    trackMasterTrackLayoutId?: number;
}