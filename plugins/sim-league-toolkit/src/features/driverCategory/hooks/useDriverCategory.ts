import {useQuery} from '@tanstack/react-query';

import {driverCategoryQueryKeys} from '../api/driverCategoryQueryKeys';
import {driverCategoryApi} from '../api/driverCategoryApi';

export const useDriverCategory = (id: number) => {
    return useQuery({
        queryKey: driverCategoryQueryKeys.single(id),
        queryFn: () => driverCategoryApi.getById(id),
    });
};
