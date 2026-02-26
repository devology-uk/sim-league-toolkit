export interface EventSession {
    id: number;
    eventRefId: number;
    eventType: string;
    name: string;
    sessionType: string;
    startTime: string;
    duration: number;
    sortOrder: number;
    attributes: Record<string, unknown>;
}
