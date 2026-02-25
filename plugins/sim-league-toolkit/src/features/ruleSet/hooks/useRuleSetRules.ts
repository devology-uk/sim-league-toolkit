import {useQuery} from '@tanstack/react-query';

import {ruleSetQueryKeys} from '../api/ruleSetQueryKeys';
import {ruleSetApi} from '../api/ruleSetApi';

export const useRuleSetRules = (ruleSetId: number) => {
    return useQuery({
                        queryKey: ruleSetQueryKeys.rules(ruleSetId),
                        queryFn: () => ruleSetApi.listRules(ruleSetId),
                    });
};