import {ApiClient} from '../../../api';

import {Trophy, TrophyPreviewResult} from '../';

const standaloneEventRoot = '/standalone-event';
const championshipEventRoot = '/championship-event';
const championshipRoot = '/championship';

const endpoints = {
    previewStandaloneEvent: (id: number) => `${standaloneEventRoot}/${id}/trophies/preview`,
    awardStandaloneEvent: (id: number) => `${standaloneEventRoot}/${id}/trophies/award`,
    previewChampionshipEvent: (id: number) => `${championshipEventRoot}/${id}/trophies/preview`,
    awardChampionshipEvent: (id: number) => `${championshipEventRoot}/${id}/trophies/award`,
    previewChampionship: (id: number) => `${championshipRoot}/${id}/trophies/preview`,
    awardChampionship: (id: number) => `${championshipRoot}/${id}/trophies/award`,
};

export const trophyApi = {
    previewStandaloneEventTrophies: async (id: number): Promise<TrophyPreviewResult> => {
        const response = await ApiClient.get<TrophyPreviewResult>(endpoints.previewStandaloneEvent(id));
        if (!response.success) {
            throw new Error(`Failed to preview trophies for standalone event ${id}`);
        }
        return response.data;
    },

    awardStandaloneEventTrophies: async (id: number): Promise<Trophy[]> => {
        const response = await ApiClient.post<Trophy[]>(endpoints.awardStandaloneEvent(id), {});
        if (!response.success) {
            throw new Error(`Failed to award trophies for standalone event ${id}`);
        }
        return response.data ?? [];
    },

    previewChampionshipEventTrophies: async (id: number): Promise<TrophyPreviewResult> => {
        const response = await ApiClient.get<TrophyPreviewResult>(endpoints.previewChampionshipEvent(id));
        if (!response.success) {
            throw new Error(`Failed to preview trophies for championship event ${id}`);
        }
        return response.data;
    },

    awardChampionshipEventTrophies: async (id: number): Promise<Trophy[]> => {
        const response = await ApiClient.post<Trophy[]>(endpoints.awardChampionshipEvent(id), {});
        if (!response.success) {
            throw new Error(`Failed to award trophies for championship event ${id}`);
        }
        return response.data ?? [];
    },

    previewChampionshipTrophies: async (id: number): Promise<TrophyPreviewResult> => {
        const response = await ApiClient.get<TrophyPreviewResult>(endpoints.previewChampionship(id));
        if (!response.success) {
            throw new Error(`Failed to preview trophies for championship ${id}`);
        }
        return response.data;
    },

    awardChampionshipTrophies: async (id: number): Promise<Trophy[]> => {
        const response = await ApiClient.post<Trophy[]>(endpoints.awardChampionship(id), {});
        if (!response.success) {
            throw new Error(`Failed to award trophies for championship ${id}`);
        }
        return response.data ?? [];
    },
};
