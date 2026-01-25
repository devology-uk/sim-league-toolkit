import {__} from '@wordpress/i18n';
import {useState, useCallback, useEffect} from '@wordpress/element';

import {ApiClient} from '../api/ApiClient';
import {CreateResponse} from '../types/CreateResponse';
import {RuleSetRule} from '../types/RuleSetRule';
import {RuleSetRuleFormData} from '../types/RuleSetRuleFormData';
import {
    ruleSetRulesGetEndpoint,
    ruleSetRuleDeleteEndpoint,
    ruleSetRulePutEndpoint, ruleSetRulePostEndpoint
} from '../api/endpoints/ruleSetApiEndpoints';

interface UseRuleSetRulesResult {
    createRuleSetRule: (data: RuleSetRuleFormData) => Promise<number | null>;
    deleteRuleSetRule: (id: number) => Promise<boolean>;
    isLoading: boolean;
    refresh: () => Promise<void>
    ruleSetRules: RuleSetRule[];
    updateRuleSetRule: (id: number, data: RuleSetRuleFormData) => Promise<boolean>;
}

export const useRuleSetRules = (ruleSetId: number | null): UseRuleSetRulesResult => {
    const [ruleSetRules, setRuleSetRules] = useState<RuleSetRule[]>([]);
    const [isLoading, setIsLoading] = useState(false);

    const refresh = useCallback(async () => {
        if (!ruleSetId) {
            setRuleSetRules([]);
            return;
        }

        setIsLoading(true);
        const response = await ApiClient.get<RuleSetRule[]>(
            ruleSetRulesGetEndpoint(ruleSetId)
        );

        if (response.success) {
            setRuleSetRules(response.data ?? []);
        }

        setIsLoading(false);
    }, [ruleSetId]);

    const createRuleSetRule = useCallback(async (data: RuleSetRuleFormData): Promise<number | null> => {
        const response = await ApiClient.post<CreateResponse>(
            ruleSetRulePostEndpoint(ruleSetId!),
            data
        );

        if (response.success && response.data) {
            ApiClient.showSuccess(__('Rule Set Score added successfully', 'sim-league-toolkit'));
            await refresh();
            return response.data.id;
        }

        return null;
    }, [ruleSetId, refresh]);

    const deleteRuleSetRule = useCallback(async (id: number): Promise<boolean> => {
        const response = await ApiClient.delete(ruleSetRuleDeleteEndpoint(id));

        if (response.success) {
            ApiClient.showSuccess(__('Rule Set Score deleted successfully', 'sim-league-toolkit'));
            await refresh();
        }

        return response.success;
    }, [refresh]);

    const updateRuleSetRule = useCallback(async (id: number, data: RuleSetRuleFormData): Promise<boolean> => {
        const response = await ApiClient.put(ruleSetRulePutEndpoint(id), data);

        if (response.success) {
            ApiClient.showSuccess(__('Rule Set Score updated successfully', 'sim-league-toolkit'));
            await refresh();
        }

        return response.success;
    }, [refresh]);

    useEffect(() => {
        refresh().then(_ => {});
    }, [refresh]);

    return {
        createRuleSetRule,
        deleteRuleSetRule,
        isLoading,
        refresh,
        ruleSetRules,
        updateRuleSetRule,
    };
};