import {SessionType} from '../../../enums/generated/SessionType';

export interface EventSession {
    id: number;
    eventRefId: number;
    eventType: string;
    name: string;
    sessionType: SessionType;
    sortOrder: number;
    attributes: Record<string, unknown>;
}
