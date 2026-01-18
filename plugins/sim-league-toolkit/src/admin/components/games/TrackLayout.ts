import {Entity} from "../shared/Entity";

export interface TrackLayout extends Entity {
    corners: number;
    gameId: number;
    layoutId: string;
    length: number;
    name: string;
    trackId: number;
}