import {ProposedTrophy} from './ProposedTrophy';

export interface TrophyPreviewResult {
    canAward: boolean;
    reason: string | null;
    proposedTrophies: ProposedTrophy[];
}
