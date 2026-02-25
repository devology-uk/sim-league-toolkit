import {ApiClient} from '../../../api';

import {ScoringSet, ScoringSetFormData, ScoringSetScore, ScoringSetScoreFormData} from '../';

const scoringSetRootEndpoint = '/scoring-set'
const scoringSetsRootEndpoint = '/scoring-sets'
const scoringSetScoreRootEndpoint = '/scoring-set-score'

const endpoints = {
    get: scoringSetsRootEndpoint,
    getById: (id: number) => `${scoringSetRootEndpoint}/${id}`,
    create: scoringSetRootEndpoint,
    update: (id: number) => `${scoringSetRootEndpoint}/${id}`,
    delete: (id: number) => `${scoringSetRootEndpoint}/${id}`,
    getScores: (scoringSetId: number) => `${scoringSetRootEndpoint}/${scoringSetId}/scores`,
    getScoreById: (id: number) => `${scoringSetScoreRootEndpoint}/${id}`,
    createScore: (scoringSetId: number) => `${scoringSetRootEndpoint}/${scoringSetId}/scores`,
    updateScore: (id: number) => `${scoringSetScoreRootEndpoint}/${id}`,
    deleteScore: (id: number) => `${scoringSetScoreRootEndpoint}/${id}`,
}

export const scoringSetApi = {
    list: async (): Promise<ScoringSet[]> => {
        const response = await ApiClient.get<ScoringSet[]>(endpoints.get);
        if (!response.success) {
            throw new Error('Failed to fetch scoring sets');
        }
        return response.data ?? [];
    },

    getById: async (id: number): Promise<ScoringSet> => {
        const response = await ApiClient.get<ScoringSet>(endpoints.getById(id));
        if (!response.success) {
            throw new Error(`Failed to fetch score set with ${id}`);
        }
        return response.data;
    },

    create: async (data: ScoringSetFormData): Promise<number> => {
        const response = await ApiClient.post<number>(endpoints.create, data);
        if (!response.success) {
            throw new Error('Failed to create score set');
        }
        return response.data;
    },

    update: async (id: number, data: ScoringSetFormData): Promise<void> => {
        const response = await ApiClient.put<void>(endpoints.update(id), data);
        if (!response.success) {
            throw new Error(`Failed to update score set with ${id}`);
        }
    },

    delete: async (id: number): Promise<void> => {
        const response = await ApiClient.delete(endpoints.delete(id));
        if (!response.success) {
            throw new Error(`Failed to delete score set with ${id}`);
        }
    },

    listScores: async (scoringSetId: number): Promise<ScoringSetScore[]> => {
        const response = await ApiClient.get<ScoringSetScore[]>(endpoints.getScores(scoringSetId));
        if (!response.success) {
            throw new Error(`Failed to fetch scores for score set with ${scoringSetId}`);
        }
        return response.data ?? [];
    },

    getScoreById: async (id: number): Promise<ScoringSetScore> => {
        const response = await ApiClient.get<ScoringSetScore>(endpoints.getScoreById(id));
        if (!response.success) {
            throw new Error(`Failed to fetch score with ${id}`);
        }
        return response.data;
    },

    createScore: async (scoringSetId: number, data: ScoringSetScoreFormData): Promise<number> => {
        const response = await ApiClient.post<number>(endpoints.createScore(scoringSetId), data);
        if (!response.success) {
            throw new Error(`Failed to create score for score set with ${scoringSetId}`);
        }
        return response.data;
    },

    updateScore: async (id: number, data: ScoringSetScoreFormData): Promise<void> => {
        const response = await ApiClient.put<void>(endpoints.updateScore(id), data);
        if (!response.success) {
            throw new Error(`Failed to update score with ${id}`);
        }
    },

    deleteScore: async (id: number): Promise<void> => {
        const response = await ApiClient.delete(endpoints.deleteScore(id));
        if (!response.success) {
            throw new Error(`Failed to delete score with ${id}`);
        }
    },
}
