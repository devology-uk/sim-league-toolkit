import {SessionTypeConfig} from './SessionTypeConfig';

export interface GameConfig {
    gameId: string;
    gameName: string;
    sessionTypes: Record<string, SessionTypeConfig>;
}