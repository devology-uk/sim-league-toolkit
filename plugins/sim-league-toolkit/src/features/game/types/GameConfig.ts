import {SessionTypeConfig} from './SessionTypeConfig';

export interface GameServerSettingDefinition {
    name: string;
    default: string | number;
    canEditIfHosted: boolean;
}

export interface GameConfig {
    gameId: string;
    gameName: string;
    serverSettings: GameServerSettingDefinition[];
    sessionTypes: Record<string, SessionTypeConfig>;
}