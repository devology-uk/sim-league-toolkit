import {ApiClient} from '../../../api';

import {
    ChampionshipResultPenalty,
    ChampionshipResultPenaltyFormData,
    ChampionshipSessionResult,
    ChampionshipSessionResultFormData,
} from '../';

const eventSessionRoot = '/event-session';
const resultRoot = '/championship-session-result';
const penaltyRoot = '/championship-result-penalty';

const endpoints = {
    listByEventSession: (eventSessionId: number) => `${eventSessionRoot}/${eventSessionId}/championship-results`,
    create: (eventSessionId: number) => `${eventSessionRoot}/${eventSessionId}/championship-results`,
    update: (id: number) => `${resultRoot}/${id}`,
    delete: (id: number) => `${resultRoot}/${id}`,
    listPenalties: (resultId: number) => `${resultRoot}/${resultId}/penalties`,
    createPenalty: (resultId: number) => `${resultRoot}/${resultId}/penalties`,
    deletePenalty: (id: number) => `${penaltyRoot}/${id}`,
};

export const championshipSessionResultApi = {
    listByEventSession: async (eventSessionId: number): Promise<ChampionshipSessionResult[]> => {
        const response = await ApiClient.get<ChampionshipSessionResult[]>(endpoints.listByEventSession(eventSessionId));
        if (!response.success) {
            throw new Error(`Failed to fetch results for event session ${eventSessionId}`);
        }
        return response.data ?? [];
    },

    create: async (eventSessionId: number, data: ChampionshipSessionResultFormData): Promise<number> => {
        const response = await ApiClient.post<number>(endpoints.create(eventSessionId), data);
        if (!response.success) {
            throw new Error('Failed to create championship session result');
        }
        return response.data;
    },

    update: async (id: number, data: ChampionshipSessionResultFormData): Promise<void> => {
        const response = await ApiClient.put<void>(endpoints.update(id), data);
        if (!response.success) {
            throw new Error(`Failed to update championship session result with id ${id}`);
        }
    },

    delete: async (id: number): Promise<void> => {
        const response = await ApiClient.delete(endpoints.delete(id));
        if (!response.success) {
            throw new Error(`Failed to delete championship session result with id ${id}`);
        }
    },

    listPenalties: async (resultId: number): Promise<ChampionshipResultPenalty[]> => {
        const response = await ApiClient.get<ChampionshipResultPenalty[]>(endpoints.listPenalties(resultId));
        if (!response.success) {
            throw new Error(`Failed to fetch penalties for result ${resultId}`);
        }
        return response.data ?? [];
    },

    createPenalty: async (resultId: number, data: ChampionshipResultPenaltyFormData): Promise<number> => {
        const response = await ApiClient.post<number>(endpoints.createPenalty(resultId), data);
        if (!response.success) {
            throw new Error(`Failed to create penalty for result ${resultId}`);
        }
        return response.data;
    },

    deletePenalty: async (id: number): Promise<void> => {
        const response = await ApiClient.delete(endpoints.deletePenalty(id));
        if (!response.success) {
            throw new Error(`Failed to delete penalty with id ${id}`);
        }
    },
};
