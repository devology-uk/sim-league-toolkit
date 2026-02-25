import {Entity} from "../../../types/Entity";

export interface Platform extends Entity {
    name: string;
    playerIdPrefix: string;
    shortName: string;
}