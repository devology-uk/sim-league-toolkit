export interface EventSessionFormData {
    eventRefId: number;
    gameId: string;
    name: string;
    sessionType: string;
    startTime: string;
    duration: number;
    sortOrder: number;
    attributes: Record<string, unknown>;
}
