import {__} from '@wordpress/i18n';
import {useState, useEffect, useCallback} from '@wordpress/element';

import {ApiClient} from '../api/ApiClient';
import {CreateResponse} from '../types/CreateResponse';
import {RuleSet} from '../types/RuleSet';
import {RuleSetFormData} from '../types/RuleSetFormData';
import {
    ruleSetsGetEndpoint,
    ruleSetPostEndpoint,
    ruleSetDeleteEndpoint, ruleSetPutEndpoint
} from '../api/endpoints/ruleSetApiEndpoints';

interface UseRuleSetsResult {
    createRuleSet: (data: RuleSetFormData) => Promise<number | null>;
    deleteRuleSet: (id: number) => Promise<boolean>;
    findRuleSet: (id: number) => RuleSet | null;
    isLoading: boolean;
    refresh: () => Promise<void>;
    ruleSets: RuleSet[];
    updateRuleSet: (id: number, data: RuleSetFormData) => Promise<boolean>;
}

export const useRuleSets = (): UseRuleSetsResult => {
    const [ruleSets, setRuleSets] = useState<RuleSet[]>([]);
    const [isLoading, setIsLoading] = useState(false);

    const refresh = useCallback(async () => {
        setIsLoading(true);

        const apiResponse = await ApiClient.get<RuleSet[]>(ruleSetsGetEndpoint());
        if (apiResponse.success) {
            setRuleSets(apiResponse.data ?? []);
        }
        setIsLoading(false);

    }, []);

    const createRuleSet = useCallback(async (data: RuleSetFormData): Promise<number | null> => {
        const apiResponse = await ApiClient.post<CreateResponse>(ruleSetPostEndpoint(), data);
        if (apiResponse.success && apiResponse.data) {

            ApiClient.showSuccess(__('Rule Set created successfully', 'sim-league-toolkit'));
            await refresh();
            return apiResponse.data.id;
        }

        return null;

    }, [refresh]);

    const deleteRuleSet = useCallback(async (id: number): Promise<boolean> => {
        const apiResponse = await ApiClient.delete(ruleSetDeleteEndpoint(id));
        if (apiResponse.success) {
            ApiClient.showSuccess(__('Rule Set deleted successfully', 'sim-league-toolkit'));
            await refresh();
        }
        return apiResponse.success;
    }, [refresh]);

    const updateRuleSet = useCallback(async (id: number, data: RuleSetFormData): Promise<boolean> => {
        const apiResponse = await ApiClient.put(ruleSetPutEndpoint(id), data);
        if (apiResponse.success) {
            ApiClient.showSuccess(__('Rule Set updated successfully', 'sim-league-toolkit'));
            await refresh();
        }
        return apiResponse.success;
    }, [refresh]);

    const findRuleSet = (id: number): RuleSet => ruleSets.find(rs => rs.id === id) ?? null;

    useEffect(() => {
        refresh().then(_ => {
        });
    }, [refresh]);

    return {
        createRuleSet,
        deleteRuleSet,
        findRuleSet,
        ruleSets,
        isLoading,
        refresh,
        updateRuleSet,
    };

};

