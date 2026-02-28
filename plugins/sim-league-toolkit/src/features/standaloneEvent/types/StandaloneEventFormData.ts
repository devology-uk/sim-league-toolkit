export interface StandaloneEventFormData {
    name: string;
    description: string;
    bannerImageUrl: string;
    gameId: number;
    trackId: number;
    trackLayoutId?: number;
    eventDate: Date;
    startTime: string;
    isActive: boolean;
    maxEntrants: number;
    ruleSetId?: number;
    scoringSetId?: number;
}
