import {useMutation, useQueryClient} from '@tanstack/react-query';

import {scoringSetQueryKeys} from '../api/scoringSetQueryKeys';
import {scoringSetApi} from '../api/scoringSetApi';
import {ScoringSetFormData} from '../';

export const useCreateScoringSet = () => {
    const queryClient = useQueryClient();

    return useMutation({
        mutationFn: (data: ScoringSetFormData) => scoringSetApi.create(data),
        onSuccess: () => {
            queryClient.invalidateQueries({queryKey: scoringSetQueryKeys.all}).then(() => {});
        },
    });
};
