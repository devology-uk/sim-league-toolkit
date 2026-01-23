import {Entity} from "./Entity";

export interface DriverCategory extends Entity {
    name: string;
    plaque: string;
    participationRequirement: number;
}