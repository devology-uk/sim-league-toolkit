import {useMutation, useQueryClient} from '@tanstack/react-query';

import {ruleSetQueryKeys} from '../api/ruleSetQueryKeys';
import {ruleSetApi} from '../api/ruleSetApi';
import {RuleSetRuleFormData} from '../';

export const useCreateRuleSetRule = (ruleSetId: number) => {
    const queryClient = useQueryClient();

    return useMutation({
        mutationFn: (data: RuleSetRuleFormData) => ruleSetApi.createRule(ruleSetId, data),
        onSuccess: () => {
            queryClient.invalidateQueries({queryKey: ruleSetQueryKeys.rules(ruleSetId)}).then(() => {});
        },
    });
};
