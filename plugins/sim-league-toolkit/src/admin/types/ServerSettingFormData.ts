import {Entity} from './Entity';

export interface ServerSettingFormData extends Entity {
    serverId: number;
    settingName: string;
    settingValue: string;
}