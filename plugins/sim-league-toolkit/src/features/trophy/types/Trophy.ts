import {TrophyAwardType} from '../../../enums/generated/TrophyAwardType';
import {TrophyScope} from '../../../enums/generated/TrophyScope';

export interface Trophy {
    id: number;
    memberId: number;
    scope: TrophyScope;
    scopeId: number;
    eventSessionId: number | null;
    eventClassId: number | null;
    awardType: TrophyAwardType;
    awardedDate: string;
    memberName: string;
    className: string | null;
    sessionName: string | null;
}
