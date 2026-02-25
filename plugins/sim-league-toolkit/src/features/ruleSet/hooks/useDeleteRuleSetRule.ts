import {useMutation, useQueryClient} from '@tanstack/react-query';

import {ruleSetQueryKeys} from '../api/ruleSetQueryKeys';
import {ruleSetApi} from '../api/ruleSetApi';

export const useDeleteRuleSetRule = (ruleSetId: number) => {
    const queryClient = useQueryClient();

    return useMutation({
        mutationFn: (id: number) => ruleSetApi.deleteRule(id),
        onSuccess: () => {
            queryClient.invalidateQueries({queryKey: ruleSetQueryKeys.rules(ruleSetId)}).then(() => {});
        },
    });
};
