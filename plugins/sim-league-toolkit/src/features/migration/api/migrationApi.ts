import {ApiClient} from '../../../api';

import {MigrationImporterRunResult, MigrationStatus} from '../';

const migrationRoot = '/migration';

const endpoints = {
    status: () => `${migrationRoot}/status`,
    runAll: () => `${migrationRoot}/run-all`,
};

export const migrationApi = {
    getStatus: async (): Promise<MigrationStatus> => {
        const response = await ApiClient.get<MigrationStatus>(endpoints.status());
        if (!response.success) {
            throw new Error('Failed to fetch migration status');
        }
        return response.data;
    },

    runAll: async (): Promise<MigrationImporterRunResult[]> => {
        const response = await ApiClient.post<MigrationImporterRunResult[]>(endpoints.runAll(), {});
        if (!response.success) {
            throw new Error('Failed to run migration');
        }
        return response.data ?? [];
    },
};
