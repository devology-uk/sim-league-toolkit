import {useQuery} from '@tanstack/react-query';

import {ruleSetQueryKeys} from '../api/ruleSetQueryKeys';
import {ruleSetApi} from '../api/ruleSetApi';

export const useRuleSetRule = (id: number) => {
    return useQuery({
                        queryKey: ruleSetQueryKeys.rule(id),
                        queryFn: () => ruleSetApi.getRuleById(id),
                    });
};