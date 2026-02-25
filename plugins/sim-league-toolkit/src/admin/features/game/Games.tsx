import {__} from '@wordpress/i18n';

import {DataView} from 'primereact/dataview';
import {Panel} from 'primereact/panel';

import {BusyIndicator} from '../../components/shared/BusyIndicator';
import {Game, useGames} from '../../../features/game';
import {GameCard} from './GameCard';

export const Games = () => {

    const {data, isLoading} = useGames();

    const itemTemplate = (item: Game) => {
        return <GameCard game={item} key={item.id}/>;
    };

    return <>
        <BusyIndicator isBusy={isLoading}/>
        <Panel header={__('Games', 'sim-league-toolkit')}>
            <DataView value={data} itemTemplate={itemTemplate} layout='grid'
                      emptyMessage={__('No games found', 'sim-league-toolkit')}/>
        </Panel>
    </>;
};