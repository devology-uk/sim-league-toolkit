export const gameConfigsGetEndPoint = (): string => {
    return 'game-config';
};

export const gameConfigGetEndPoint = (gameKey: string): string => {
    return `game-config/${gameKey}`;
};