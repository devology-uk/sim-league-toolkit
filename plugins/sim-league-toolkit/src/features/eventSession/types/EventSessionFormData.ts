import {SessionType} from '../../../enums/generated/SessionType';

export interface EventSessionFormData {
    eventRefId: number;
    gameId: string;
    name: string;
    sessionType: SessionType;
    sortOrder: number;
    attributes: Record<string, unknown>;
}
