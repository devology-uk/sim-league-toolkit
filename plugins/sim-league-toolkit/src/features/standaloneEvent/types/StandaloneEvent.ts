export interface StandaloneEvent {
    id: number;
    eventRefId: number;
    name: string;
    description: string;
    bannerImageUrl: string;
    gameId: number;
    game?: string;
    trackId: number;
    track?: string;
    trackLayoutId?: number;
    trackLayout?: string;
    eventDate: string;
    startTime: string;
    isActive: boolean;
    isPublic: boolean;
    maxEntrants: number;
    ruleSetId?: number;
    ruleSet?: string;
    scoringSetId?: number;
    scoringSet?: string;
}
