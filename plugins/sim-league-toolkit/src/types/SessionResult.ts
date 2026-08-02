import {ResultStatus} from '../enums/generated/ResultStatus';

export interface SessionResult {
    id?: number;
    eventSessionId: number;
    position: number | null;
    totalTimeMs: number | null;
    fastestLapMs: number | null;
    sector1TimeMs: number | null;
    sector2TimeMs: number | null;
    sector3TimeMs: number | null;
    lapsCompleted: number;
    status: ResultStatus;
    points: number | null;
    memberName: string;
    raceNumber: number;
    className: string | null;
}
