import {Entity} from "../shared/Entity";

export interface ServerSetting extends Entity{
    serverId: number;
    settingName: string;
    settingValue: string;
}