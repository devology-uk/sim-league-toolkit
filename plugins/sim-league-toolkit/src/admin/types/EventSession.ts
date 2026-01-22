import { SessionType } from '../generated/enums';

export interface EventSession {
    id?: number;
    eventRefId: number;
    eventType: string;
    name: string;
    sessionType: SessionType;
    startTime: string;
    duration: number;
    sortOrder: number;
    attributes: Record<string, unknown>;
}