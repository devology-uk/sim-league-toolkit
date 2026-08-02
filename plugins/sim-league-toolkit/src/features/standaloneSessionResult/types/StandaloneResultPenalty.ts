import {ResultPenalty} from '../../../types/ResultPenalty';

export interface StandaloneResultPenalty extends ResultPenalty {
    standaloneSessionResultId: number;
}
