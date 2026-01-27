
export interface ChampionshipEventFormData {
    description: string;
    isActive: boolean;
    name: string;
    ruleSetId: number;
    startDateTime: string;
    trackId: number;
    trackLayoutId?: number;
}