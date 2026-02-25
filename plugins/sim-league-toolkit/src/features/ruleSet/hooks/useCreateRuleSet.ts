import {useMutation, useQueryClient} from '@tanstack/react-query';

import {ruleSetQueryKeys} from '../api/ruleSetQueryKeys';
import {ruleSetApi} from '../api/ruleSetApi';
import {RuleSetFormData} from '../';

export const useCreateRuleSet = () => {
    const queryClient = useQueryClient();

    return useMutation({
        mutationFn: (data: RuleSetFormData) => ruleSetApi.create(data),
        onSuccess: () => {
            queryClient.invalidateQueries({queryKey: ruleSetQueryKeys.all}).then(() => {});
        },
    });
};
