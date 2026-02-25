import {ApiClient} from '../../../api';
import type {Game} from '../types/Game';
import type {GameConfig} from '../types/GameConfig';

const gameRootEndpoint = '/game';
const gamesRootEndpoint = '/games';
const gameConfigRootEndpoint =  '/game-config';

const endpoints = {
    get: gamesRootEndpoint,
    getById: (gameId: number)=> `${gameRootEndpoint}/${gameId}`,
    getConfig: (gameKey: string) => `${gameConfigRootEndpoint}/${gameKey}`,
}

export const gameApi = {
    list: async (): Promise<Game[]> => {
        const response = await ApiClient.get<Game[]>(endpoints.get);
        if (!response.success) {
            throw new Error('Failed to fetch games');
        }
        return response.data ?? [];
    },

    getById: async (id: number): Promise<Game> => {
        const response = await ApiClient.get<Game>(endpoints.getById(id));
        if (!response.success) {
            throw new Error(`Failed to fetch game with ${id}`);
        }
        return response.data;
    },

    getConfig: async (gameKey: string): Promise<GameConfig> => {
        const response = await ApiClient.get<GameConfig>(endpoints.getConfig(gameKey));
        if (!response.success) {
            throw new Error(`Failed to fetch game config for ${gameKey}`);
        }
        return response.data;
    },
}