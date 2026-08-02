import {ApiClient} from '../../../api';

import {
    StandaloneResultPenalty,
    StandaloneResultPenaltyFormData,
    StandaloneSessionResult,
    StandaloneSessionResultFormData,
} from '../';

const eventSessionRoot = '/event-session';
const resultRoot = '/standalone-session-result';
const penaltyRoot = '/standalone-result-penalty';

const endpoints = {
    listByEventSession: (eventSessionId: number) => `${eventSessionRoot}/${eventSessionId}/standalone-results`,
    create: (eventSessionId: number) => `${eventSessionRoot}/${eventSessionId}/standalone-results`,
    update: (id: number) => `${resultRoot}/${id}`,
    delete: (id: number) => `${resultRoot}/${id}`,
    listPenalties: (resultId: number) => `${resultRoot}/${resultId}/penalties`,
    createPenalty: (resultId: number) => `${resultRoot}/${resultId}/penalties`,
    deletePenalty: (id: number) => `${penaltyRoot}/${id}`,
};

export const standaloneSessionResultApi = {
    listByEventSession: async (eventSessionId: number): Promise<StandaloneSessionResult[]> => {
        const response = await ApiClient.get<StandaloneSessionResult[]>(endpoints.listByEventSession(eventSessionId));
        if (!response.success) {
            throw new Error(`Failed to fetch results for event session ${eventSessionId}`);
        }
        return response.data ?? [];
    },

    create: async (eventSessionId: number, data: StandaloneSessionResultFormData): Promise<number> => {
        const response = await ApiClient.post<number>(endpoints.create(eventSessionId), data);
        if (!response.success) {
            throw new Error('Failed to create standalone session result');
        }
        return response.data;
    },

    update: async (id: number, data: StandaloneSessionResultFormData): Promise<void> => {
        const response = await ApiClient.put<void>(endpoints.update(id), data);
        if (!response.success) {
            throw new Error(`Failed to update standalone session result with id ${id}`);
        }
    },

    delete: async (id: number): Promise<void> => {
        const response = await ApiClient.delete(endpoints.delete(id));
        if (!response.success) {
            throw new Error(`Failed to delete standalone session result with id ${id}`);
        }
    },

    listPenalties: async (resultId: number): Promise<StandaloneResultPenalty[]> => {
        const response = await ApiClient.get<StandaloneResultPenalty[]>(endpoints.listPenalties(resultId));
        if (!response.success) {
            throw new Error(`Failed to fetch penalties for result ${resultId}`);
        }
        return response.data ?? [];
    },

    createPenalty: async (resultId: number, data: StandaloneResultPenaltyFormData): Promise<number> => {
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
