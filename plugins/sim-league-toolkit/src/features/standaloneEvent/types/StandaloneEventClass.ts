export interface StandaloneEventClass {
    standaloneEventId: number;
    eventClassId: number;
    name: string;
    carClass: string;
    driverCategory: string;
    driverCategoryId: number;
    game: string;
    gameId: number;
    isBuiltIn: boolean;
    isInUse: boolean;
    isSingleCarClass: boolean;
    singleCarId?: number;
    singleCarName?: string;
}
