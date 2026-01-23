import {getApiPath} from './apiRoutes';

export const ruleDeleteRoute = (ruleId: number): any => {
    return getApiPath(`rule-set/rules/${ruleId}`);
}

export const rulePostRoute = (): any => {
    return getApiPath('rule-set/rules');
}

export const ruleSetDeleteRoute = (ruleSetId: number): any => {
    return getApiPath(`rule-set/${ruleSetId}`);
}

export const ruleSetGetRoute = (ruleSetId: number): any => {
    return getApiPath(`rule-set/${ruleSetId}`);
}

export const rulesGetRoute = (ruleSetId: number): any => {
    return getApiPath(`rule-set/${ruleSetId}/rules`);
};

export const ruleSetPostRoute = (): any => {
    return getApiPath('rule-set');
}

export const ruleSetsGetRoute = () => {
    return getApiPath('rule-set');
}

