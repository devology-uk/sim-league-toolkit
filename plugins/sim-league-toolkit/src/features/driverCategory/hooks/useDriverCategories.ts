import {useQuery} from '@tanstack/react-query';

import {driverCategoryQueryKeys} from '../api/driverCategoryQueryKeys';
import {driverCategoryApi} from '../api/driverCategoryApi';

export const useDriverCategories = () => {
    return useQuery({
        queryKey: driverCategoryQueryKeys.all,
        queryFn: driverCategoryApi.list,
    });
};
