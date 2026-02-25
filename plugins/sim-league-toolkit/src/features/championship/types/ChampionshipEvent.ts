export interface ChampionshipEvent {
    id: number;
    bannerImageUrl: string;
    championship?: string;
    championshipId: number;
    description: string;
    isActive: boolean;
    isCompleted: boolean;
    name: string;
    ruleSet?: string;
    ruleSetId: number;
    startDateTime: string;
    track?: string;
    trackId: number;
    trackLayout?: string;
    trackLayoutId?: number;
}
