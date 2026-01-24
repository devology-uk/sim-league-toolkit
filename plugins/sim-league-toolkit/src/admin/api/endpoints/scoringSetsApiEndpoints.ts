const scoringSetRootEndpoint = () => {
    return 'scoring-set';
};

const scoringSetsRootEndpoint = () => {
    return 'scoring-sets';
};

const scoringSetScoreRootEndpoint = () => {
    return 'scoring-set-score';
};

export const scoringSetDeleteEndpoint = (serverId: number): string => {
    return `${scoringSetRootEndpoint()}/${serverId}`;
};

export const scoringSetsGetEndpoint = () => {
    return scoringSetsRootEndpoint();
};

export const scoringSetPostEndpoint = () => {
    return scoringSetsRootEndpoint();
};

export const scoringSetPutEndpoint = (serverId: number): string => {
    return `${scoringSetRootEndpoint()}/${serverId}`;
};

export const scoringSetScoreDeleteEndpoint = (scoringSetScoreId: number): string => {
    return `${scoringSetScoreRootEndpoint()}/${scoringSetScoreId}`;
};

export const scoringSetScoresGetEndpoint = (serverId: number): string => {
    return `${scoringSetRootEndpoint()}/${serverId}/scores`;
};

export const scoringSetScorePostEndpoint = (serverId: number): string => {
    return `${scoringSetRootEndpoint()}/${serverId}/scores`;
};

export const scoringSetScorePutEndpoint = (scoringSetScoreId: number): string => {
    return `${scoringSetScoreRootEndpoint()}/${scoringSetScoreId}`;
};
