import {useMutation, useQueryClient} from '@tanstack/react-query';

import {scoringSetQueryKeys} from '../api/scoringSetQueryKeys';
import {scoringSetApi} from '../api/scoringSetApi';
import {ScoringSetFormData} from '../';

export const useUpdateScoringSet = () => {
    const queryClient = useQueryClient();

    return useMutation({
        mutationFn: ({id, data}: { id: number; data: ScoringSetFormData }) => scoringSetApi.update(id, data),
        onSuccess: (_, {id}) => {
            queryClient.invalidateQueries({queryKey: scoringSetQueryKeys.all}).then(() => {});
            queryClient.invalidateQueries({queryKey: scoringSetQueryKeys.single(id)}).then(() => {});
        },
    });
};
