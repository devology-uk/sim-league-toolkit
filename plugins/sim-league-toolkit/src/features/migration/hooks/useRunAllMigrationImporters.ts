import {useMutation, useQueryClient} from '@tanstack/react-query';

import {migrationQueryKeys} from '../api/migrationQueryKeys';
import {migrationApi} from '../api/migrationApi';

export const useRunAllMigrationImporters = () => {
    const queryClient = useQueryClient();

    return useMutation({
        mutationFn: () => migrationApi.runAll(),
        onSuccess: async () => {
            await queryClient.invalidateQueries({queryKey: migrationQueryKeys.status()});
        },
    });
};
