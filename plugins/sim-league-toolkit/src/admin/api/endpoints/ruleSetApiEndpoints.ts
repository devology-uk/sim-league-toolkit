const ruleSetRootEndpoint = '/rule-set'
const ruleSetsRootEndpoint = '/rule-sets'
const ruleSetRuleRootEndpoint = '/rule-set-rule'

export const ruleSetDeleteEndpoint = (ruleSetId: number): string => {
    return `${ruleSetRootEndpoint}/${ruleSetId}`;
};

export const ruleSetsGetEndpoint = () => {
    return ruleSetsRootEndpoint;
};

export const ruleSetPostEndpoint = () => {
    return ruleSetsRootEndpoint;
};

export const ruleSetPutEndpoint = (ruleSetId: number): string => {
    return `${ruleSetRootEndpoint}/${ruleSetId}`;
};

export const ruleSetRuleDeleteEndpoint = (ruleSetRuleId: number): string => {
    return `${ruleSetRuleRootEndpoint}/${ruleSetRuleId}`;
};

export const ruleSetRulesGetEndpoint = (ruleSetId: number): string => {
    return `${ruleSetRootEndpoint}/${ruleSetId}/rules`;
};

export const ruleSetRulePostEndpoint = (ruleSetId: number): string => {
    return `${ruleSetRootEndpoint}/${ruleSetId}/rules`;
};

export const ruleSetRulePutEndpoint = (ruleSetRuleId: number): string => {
    return `${ruleSetRuleRootEndpoint}/${ruleSetRuleId}`;
};