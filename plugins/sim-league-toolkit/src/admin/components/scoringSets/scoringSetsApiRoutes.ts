import {getApiPath} from '../shared/apiRoutes';

export const scoresGetRoute = (scoringSetId: number): any => {
    return getApiPath(`scoring-set/${scoringSetId}/scores`);
}

export const scoreDeleteRoute = (scoreId: number): any => {
    return getApiPath(`scoring-set/scores/${scoreId}`);
}

export const scorePostRoute = (): any => {
    return getApiPath('scoring-set/scores');
}

export const scoringSetDeleteRoute = (scoringSetId: number): any => {
    return getApiPath(`scoring-set/${scoringSetId}`);
}

export const scoringSetGetRoute = (scoringSetId: number): any => {
    return getApiPath(`scoring-set/${scoringSetId}`);
}

export const scoringSetPostRoute = (): any => {
    return getApiPath('scoring-set');
}

export const scoringSetsGetRoute = (): any => {
    return getApiPath('scoring-set');
}

