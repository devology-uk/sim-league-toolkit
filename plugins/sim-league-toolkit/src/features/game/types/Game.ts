import {Entity} from "../../../admin/types/Entity";

export interface Game extends Entity {
    gameKey: string;
    latestVersion: string;
    name: string;
    published: boolean;
    supportsLayouts: boolean;
    supportsResultUpload: boolean;
}