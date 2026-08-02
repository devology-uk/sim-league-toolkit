import {useQuery} from '@tanstack/react-query';

import {migrationQueryKeys} from '../api/migrationQueryKeys';
import {migrationApi} from '../api/migrationApi';

export const useMigrationStatus = () => {
    return useQuery({
        queryKey: migrationQueryKeys.status(),
        queryFn: () => migrationApi.getStatus(),
    });
};
