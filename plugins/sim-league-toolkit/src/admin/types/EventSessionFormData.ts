import { SessionType } from '../generated/enums';

export interface EventSessionFormData {
    eventRefId: number;
    gameId: string;
    name: string;
    sessionType: SessionType;
    startTime: string;
    duration: number;
    sortOrder: number;
    attributes: Record<string, unknown>;
}