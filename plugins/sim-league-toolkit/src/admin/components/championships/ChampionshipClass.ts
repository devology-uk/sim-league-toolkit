export interface ChampionshipClass {
    carClass: string;
    championshipId: number;
    driverCategory?: string;
    driverCategoryId: number;
    eventClassId: number;
    game?: string;
    gameId: number;
    isBuiltIn?: boolean;
    isInUse?: boolean;
    isSingleCarClass: boolean;
    name: string;
    singleCarId?: number;
    singleCarName?: string;
}