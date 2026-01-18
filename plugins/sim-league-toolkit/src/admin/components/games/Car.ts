import {Entity} from "../shared/Entity";

export interface Car extends Entity {
    carClass: string;
    carKey: string;
    gameId: number;
    manufacturer: string;
    name: string;
    year: number;
}