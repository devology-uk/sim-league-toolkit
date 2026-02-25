import {useMutation, useQueryClient} from '@tanstack/react-query';

import {ruleSetQueryKeys} from '../api/ruleSetQueryKeys';
import {ruleSetApi} from '../api/ruleSetApi';
import {RuleSetFormData} from '../';

export const useUpdateRuleSet = () => {
    const queryClient = useQueryClient();

    return useMutation({
        mutationFn: ({id, data}: { id: number; data: RuleSetFormData }) => ruleSetApi.update(id, data),
        onSuccess: (_, {id}) => {
            queryClient.invalidateQueries({queryKey: ruleSetQueryKeys.all}).then(() => {});
            queryClient.invalidateQueries({queryKey: ruleSetQueryKeys.single(id)}).then(() => {});
        },
    });
};
