const driverCategoriesRootEndpoint = '/driver-categories';
const eventClassRootEndpoint = '/event-class';
const eventClassesRootEndpoint = '/event-classes';

export const driverCategoriesGetEndpoint = () => {
    return driverCategoriesRootEndpoint;
};

export const eventClassDeleteEndpoint = (eventClassId: number) => {
    return `${eventClassRootEndpoint}/${eventClassId}`;
};

export const eventClassesGetEndpoint = (): string => {
    return eventClassesRootEndpoint;
};

export const eventClassPostEndpoint = (): string => {
    return eventClassRootEndpoint;
};

export const eventClassPutEndpoint = (eventClassId: number): string => {
    return `${eventClassRootEndpoint}/${eventClassId}`;
};