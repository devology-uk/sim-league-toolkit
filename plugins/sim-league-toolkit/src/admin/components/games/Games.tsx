import {__} from '@wordpress/i18n';
import {useEffect, useState} from '@wordpress/element';
import apiFetch from '@wordpress/api-fetch';

import {DataView} from 'primereact/dataview';
import {Panel} from 'primereact/panel';

import {BusyIndicator} from '../shared/BusyIndicator';
import {Game} from '../../types/Game';
import {GameCard} from './GameCard';
import {gamesGetRoute} from '../../api/routes/gameApiRoutes';
import {HttpMethod} from '../../enums/HttpMethod';

export const Games = () => {

    const [isBusy, setIsBusy] = useState(false);
    const [games, setGames] = useState([]);

    useEffect(() => {
        loadTableData();
    }, []);

    const loadTableData = () => {
        setIsBusy(true);
        apiFetch({
                     path: gamesGetRoute(),
                     method: HttpMethod.GET,
                 }).then((r: Game[]) => {
            setGames(r ?? []);
            setIsBusy(false);
        });
    };

    const itemTemplate = (item: Game) => {
        return <GameCard game={item} key={item.id}/>;
    };

    return <>
        <BusyIndicator isBusy={isBusy}/>
        <Panel header={__('Games', 'sim-league-toolkit')}>
            <DataView value={games} itemTemplate={itemTemplate} layout='grid'
                      emptyMessage={__('No games found', 'sim-league-toolkit')}/>
        </Panel>
    </>;
};