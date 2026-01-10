import apiFetch from '@wordpress/api-fetch';
import {useEffect, useState} from '@wordpress/element';
import {__} from '@wordpress/i18n';

import {DataView} from 'primereact/dataview';
import {Panel} from 'primereact/panel';

import {BusyIndicator} from "../shared/BusyIndicator";
import {Game} from "./Game";
import {GameCard} from './GameCard';

export const Games = () => {

    const [isBusy, setIsBusy] = useState(false);
    const [games, setGames] = useState([]);

    useEffect(() => {
        loadTableData();
    }, []);


    const loadTableData = () => {
        setIsBusy(true);
        apiFetch({path: '/sltk/v1/game'}).then((r: Game[]) => {
            setGames(r ?? []);
            setIsBusy(false);
        });
    };

    const itemTemplate = (item: Game) => {
        return <GameCard game={item} key={item.id}/>
    }

    return <>
        <BusyIndicator isBusy={isBusy}/>
        <Panel header={__('Games', 'sim-league-toolkit')}>
            <DataView value={games} itemTemplate={itemTemplate} layout='grid'
                      emptyMessage={__('No games found', 'sim-league-toolkit')}/>
        </Panel>
    </>
}