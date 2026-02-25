import {useMutation, useQueryClient} from '@tanstack/react-query';

import {ruleSetQueryKeys} from '../api/ruleSetQueryKeys';
import {ruleSetApi} from '../api/ruleSetApi';

export const useDeleteRuleSet = () => {
    const queryClient = useQueryClient();

    return useMutation({
        mutationFn: (id: number) => ruleSetApi.delete(id),
        onSuccess: () => {
            queryClient.invalidateQueries({queryKey: ruleSetQueryKeys.all}).then(() => {});
        },
    });
};
