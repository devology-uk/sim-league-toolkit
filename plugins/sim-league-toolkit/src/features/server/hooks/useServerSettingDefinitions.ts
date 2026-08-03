import {useMemo} from '@wordpress/element';

import {useGameConfig} from '../../game';
import {ServerSettingDefinition} from '../types/ServerSettingDefinition';

export const useServerSettingDefinitions = (gameKey: string): ServerSettingDefinition[] => {
    const {data: gameConfig} = useGameConfig(gameKey);

    return useMemo(() => {
        return (gameConfig?.serverSettings ?? []).map((setting): ServerSettingDefinition => ({
            name: setting.name,
            default: String(setting.default),
            editableIfHosted: setting.canEditIfHosted,
        }));
    }, [gameConfig]);
};
