import {TrophyAwardType} from '../../../enums/generated/TrophyAwardType';

export interface ProposedTrophy {
    userId: number;
    memberName: string;
    awardType: TrophyAwardType;
    eventClassId: number | null;
    className: string | null;
    eventSessionId: number | null;
    sessionName: string | null;
}
