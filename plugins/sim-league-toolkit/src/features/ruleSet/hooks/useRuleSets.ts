import {useQuery} from '@tanstack/react-query';
import {ruleSetQueryKeys} from '../api/ruleSetQueryKeys';
import {ruleSetApi} from '../api/ruleSetApi';

export const useRuleSets = () => {
    return useQuery({
                        queryKey: ruleSetQueryKeys.all,
                        queryFn: ruleSetApi.list,
                    });
};