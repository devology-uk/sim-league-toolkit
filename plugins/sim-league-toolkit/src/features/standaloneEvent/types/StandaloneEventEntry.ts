export interface StandaloneEventEntry {
    id: number;
    standaloneEventId: number;
    userId: number;
    carId: number;
    eventClassId?: number;
    memberName: string;
    firstName: string;
    lastName: string;
    raceNumber: number;
    avatarUrl: string;
    carName: string;
    className?: string;
}
