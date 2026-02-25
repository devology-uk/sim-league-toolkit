import {useMutation, useQueryClient} from '@tanstack/react-query';

import {ruleSetQueryKeys} from '../api/ruleSetQueryKeys';
import {ruleSetApi} from '../api/ruleSetApi';
import {RuleSetRuleFormData} from '../';

export const useUpdateRuleSetRule = (ruleSetId: number) => {
    const queryClient = useQueryClient();

    return useMutation({
        mutationFn: ({id, data}: { id: number; data: RuleSetRuleFormData }) => ruleSetApi.updateRule(id, data),
        onSuccess: (_, {id}) => {
            queryClient.invalidateQueries({queryKey: ruleSetQueryKeys.rules(ruleSetId)}).then(() => {});
            queryClient.invalidateQueries({queryKey: ruleSetQueryKeys.rule(id)}).then(() => {});
        },
    });
};
