const scoringSetRootEndpoint = () => {
    return '/scoring-set';
};

const scoringSetsRootEndpoint = () => {
    return '/scoring-sets';
};

const scoringSetScoreRootEndpoint = () => {
    return '/scoring-set-score';
};

export const scoringSetDeleteEndpoint = (scoringSetId: number): string => {
    return `${scoringSetRootEndpoint()}/${scoringSetId}`;
};

export const scoringSetsGetEndpoint = () => {
    return scoringSetsRootEndpoint();
};

export const scoringSetPostEndpoint = () => {
    return scoringSetRootEndpoint();
};

export const scoringSetPutEndpoint = (scoringSetId: number): string => {
    return `${scoringSetRootEndpoint()}/${scoringSetId}`;
};

export const scoringSetScoreDeleteEndpoint = (scoringSetScoreId: number): string => {
    return `${scoringSetScoreRootEndpoint()}/${scoringSetScoreId}`;
};

export const scoringSetScoresGetEndpoint = (scoringSetId: number): string => {
    return `${scoringSetRootEndpoint()}/${scoringSetId}/scores`;
};

export const scoringSetScorePostEndpoint = (scoringSetId: number): string => {
    return `${scoringSetRootEndpoint()}/${scoringSetId}/scores`;
};

export const scoringSetScorePutEndpoint = (scoringSetScoreId: number): string => {
    return `${scoringSetScoreRootEndpoint()}/${scoringSetScoreId}`;
};
