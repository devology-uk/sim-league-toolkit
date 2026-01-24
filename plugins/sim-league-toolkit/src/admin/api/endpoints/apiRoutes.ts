export const getApiPath = (relativePath: string): string => {
    if (relativePath.startsWith('/')) {
        relativePath = relativePath.substring(1);
    }

    return `/sltk/v1/${relativePath}`;
};
