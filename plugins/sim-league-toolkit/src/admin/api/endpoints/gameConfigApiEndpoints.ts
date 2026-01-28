const gameConfigRootEndpoint =  '/game-config';

export const gameConfigGetEndpoint = (gameKey: string): string => {
    return `${gameConfigRootEndpoint}/${gameKey}`;
};