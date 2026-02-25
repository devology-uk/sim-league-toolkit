import {Entity} from "../../types/Entity";

export interface DriverCategory extends Entity {
    name: string;
    plaque: string;
    participationRequirement: number;
}