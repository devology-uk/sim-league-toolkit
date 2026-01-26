import {__} from '@wordpress/i18n';

import {DataView} from 'primereact/dataview';
import {Panel} from 'primereact/panel';

import {BusyIndicator} from '../shared/BusyIndicator';
import {Game} from '../../types/Game';
import {GameCard} from './GameCard';
import {useGames} from '../../hooks/useGames';

export const Games = () => {

    const {games, isLoading} = useGames();

    const itemTemplate = (item: Game) => {
        return <GameCard game={item} key={item.id}/>;
    };

    return <>
        <BusyIndicator isBusy={isLoading}/>
        <Panel header={__('Games', 'sim-league-toolkit')}>
            <DataView value={games} itemTemplate={itemTemplate} layout='grid'
                      emptyMessage={__('No games found', 'sim-league-toolkit')}/>
        </Panel>
    </>;
};