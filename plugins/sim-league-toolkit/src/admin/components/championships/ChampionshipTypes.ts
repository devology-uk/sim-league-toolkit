import {__} from '@wordpress/i18n';

export enum ChampionshipTypes {
    Standard = 0,
    Trackmaster
}

export const translateChampionshipType = (championshipType: ChampionshipTypes): string => {
    if (championshipType === ChampionshipTypes.Trackmaster) {
        return __('Track Master', 'sim-league-toolkit');
    }

    return __('Standard', 'sim-league-toolkit');
}