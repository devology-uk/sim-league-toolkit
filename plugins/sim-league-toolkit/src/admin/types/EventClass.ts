import {Entity} from "./Entity";

export interface EventClass extends Entity {
    carClass: string;
    driverCategory?: string;
    driverCategoryId: number;
    game?: string;
    gameId: number;
    isBuiltIn?: boolean;
    isInUse?: boolean;
    isSingleCarClass: boolean;
    name: string;
    singleCarId?: number;
    singleCarName?: string;
}