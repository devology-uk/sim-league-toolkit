export interface ChampionshipFormData {
    allowEntryChange: boolean;
    bannerImageUrl: string;
    championshipType: string;
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