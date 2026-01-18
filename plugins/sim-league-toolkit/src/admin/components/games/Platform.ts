import {Entity} from "../shared/Entity";

export interface Platform extends Entity {
    name: string;
    playerIdPrefix: string;
    shortName: string;
}