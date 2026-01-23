import {Entity} from "./Entity";

export interface ServerSetting extends Entity{
    serverId: number;
    settingName: string;
    settingValue: string;
}