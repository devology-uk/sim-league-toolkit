import {SessionResult} from '../../../types/SessionResult';

export interface StandaloneSessionResult extends SessionResult {
    standaloneEventEntryId: number;
}
