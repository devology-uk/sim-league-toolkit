import {__} from '@wordpress/i18n';
import {ChampionshipType} from '../types/generated/ChampionshipType';


export const translateChampionshipType = (championshipType: ChampionshipType): string => {
    if (championshipType === ChampionshipType.TRACK_MASTER) {
        return __('Track Master', 'sim-league-toolkit');
    }

    return __('Standard', 'sim-league-toolkit');
}