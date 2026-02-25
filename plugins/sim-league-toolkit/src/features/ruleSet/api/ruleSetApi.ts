import {ApiClient} from '../../../api';

import {RuleSet, RuleSetFormData, RuleSetRule, RuleSetRuleFormData} from '../';

const ruleSetRootEndpoint = '/rule-set'
const ruleSetsRootEndpoint = '/rule-sets'
const ruleSetRuleRootEndpoint = '/rule-set-rule'

const endpoints = {
    get: ruleSetsRootEndpoint,
    getById: (id: number) => `${ruleSetRootEndpoint}/${id}`,
    create: ruleSetRootEndpoint,
    update: (id: number) => `${ruleSetRootEndpoint}/${id}`,
    delete: (id: number) => `${ruleSetRootEndpoint}/${id}`,
    getRules: (ruleSetId: number) => `${ruleSetRootEndpoint}/${ruleSetId}/rules`,
    getRuleById: (id: number) => `${ruleSetRuleRootEndpoint}/${id}`,
    createRule: (ruleSetId: number) => `${ruleSetRootEndpoint}/${ruleSetId}/rules`,
    updateRule: (id: number) => `${ruleSetRuleRootEndpoint}/${id}`,
    deleteRule: (id: number) => `${ruleSetRuleRootEndpoint}/${id}`,
}

export const ruleSetApi = {
    list: async (): Promise<RuleSet[]> => {
        const response = await ApiClient.get<RuleSet[]>(endpoints.get);
        if (!response.success) {
            throw new Error('Failed to fetch rule sets');
        }
        return response.data ?? [];
    },

    getById: async (id: number): Promise<RuleSet> => {
        const response = await ApiClient.get<RuleSet>(endpoints.getById(id));
        if (!response.success) {
            throw new Error(`Failed to fetch rule set with ${id}`);
        }
        return response.data;
    },

    create: async (data: RuleSetFormData): Promise<number> => {
        const response = await ApiClient.post<number>(endpoints.create, data);
        if (!response.success) {
            throw new Error('Failed to create rule set');
        }
        return response.data;
    },

    update: async (id: number, data: RuleSetFormData): Promise<void> => {
        const response = await ApiClient.put<void>(endpoints.update(id), data);
        if (!response.success) {
            throw new Error(`Failed to update rule set with ${id}`);
        }
    },

    delete: async (id: number): Promise<void> => {
        const response = await ApiClient.delete(endpoints.delete(id));
        if (!response.success) {
            throw new Error(`Failed to delete rule set with ${id}`);
        }
    },

    listRules: async (ruleSetId: number): Promise<RuleSetRule[]> => {
        const response = await ApiClient.get<RuleSetRule[]>(endpoints.getRules(ruleSetId));
        if (!response.success) {
            throw new Error(`Failed to fetch rules for rule set with ${ruleSetId}`);
        }
        return response.data ?? [];
    },

    getRuleById: async (id: number): Promise<RuleSetRule> => {
        const response = await ApiClient.get<RuleSetRule>(endpoints.getRuleById(id));
        if (!response.success) {
            throw new Error(`Failed to fetch rule with ${id}`);
        }
        return response.data;
    },

    createRule: async (ruleSetId: number, data: RuleSetRuleFormData): Promise<number> => {
        const response = await ApiClient.post<number>(endpoints.createRule(ruleSetId), data);
        if (!response.success) {
            throw new Error(`Failed to create rule for rule set with ${ruleSetId}`);
        }
        return response.data;
    },

    updateRule: async (id: number, data: RuleSetRuleFormData): Promise<void> => {
        const response = await ApiClient.put<void>(endpoints.updateRule(id), data);
        if (!response.success) {
            throw new Error(`Failed to update rule with ${id}`);
        }
    },

    deleteRule: async (id: number): Promise<void> => {
        const response = await ApiClient.delete(endpoints.deleteRule(id));
        if (!response.success) {
            throw new Error(`Failed to delete rule with ${id}`);
        }
    },
}
