export interface ChampionshipClass {
    carClass: string;
    championshipId: number;
    driverCategory?: string;
    driverCategoryId: number;
    eventClassId: number;
    game?: string;
    gameId: number;
    isInUse?: boolean;
    isSingleCarClass: boolean;
    maxEntrants: number | null;
    name: string;
    singleCarId?: number;
    singleCarName?: string;
}
