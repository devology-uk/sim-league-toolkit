import {ResultStatus} from '../../../enums/generated/ResultStatus';

export interface ChampionshipSessionResultFormData {
    championshipEntryId: number;
    position: number | null;
    totalTimeMs: number | null;
    fastestLapMs: number | null;
    sector1TimeMs: number | null;
    sector2TimeMs: number | null;
    sector3TimeMs: number | null;
    lapsCompleted: number;
    status: ResultStatus;
}
