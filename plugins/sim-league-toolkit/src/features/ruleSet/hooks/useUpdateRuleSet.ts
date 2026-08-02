import {useMutation, useQueryClient} from '@tanstack/react-query';

import {ruleSetQueryKeys} from '../api/ruleSetQueryKeys';
import {ruleSetApi} from '../api/ruleSetApi';
import {RuleSetFormData} from '../';

export const useUpdateRuleSet = () => {
    const queryClient = useQueryClient();

    return useMutation({
        mutationFn: ({id, data}: { id: number; data: RuleSetFormData }) => ruleSetApi.update(id, data),
        onSuccess: async (_, {id}) => {
            await Promise.all([
                queryClient.invalidateQueries({queryKey: ruleSetQueryKeys.all}),
                queryClient.invalidateQueries({queryKey: ruleSetQueryKeys.single(id)}),
            ]);
        },
    });
};
