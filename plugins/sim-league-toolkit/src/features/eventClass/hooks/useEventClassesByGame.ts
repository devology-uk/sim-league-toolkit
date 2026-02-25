import {useEventClasses} from './useEventClasses';

export const useEventClassesByGame = (gameId: number) => {
    const {data, ...rest} = useEventClasses();

    return {
        data: data?.filter(ec => ec.gameId === gameId) ?? [],
        ...rest,
    };
};
