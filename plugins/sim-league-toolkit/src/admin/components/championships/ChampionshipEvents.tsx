import {useState, useEffect} from '@wordpress/element';
import apiFetch from '@wordpress/api-fetch';

import {ChampionshipEvent} from './ChampionshipEvent';
import {championshipEventsGetRoute} from '../shared/ApiRoutes';
import {HttpMethod} from '../shared/HttpMethod';
import {ChampionshipEventCard} from './ChampionshipEventCard';
import {DataView} from 'primereact/dataview';
import {__} from '@wordpress/i18n';
import {NewChampionshipEventEditor} from './NewChampionshipEventEditor';
import {BusyIndicator} from '../shared/BusyIndicator';

interface ChampionshipEventsProps {
    championshipId: number,
    gameId: number,
}

export const ChampionshipEvents = ({championshipId, gameId}: ChampionshipEventsProps) => {
    const [data, setData] = useState<ChampionshipEvent[]>([]);
    const [isAdding, setIsAdding] = useState(false);
    const [isBusy, setIsBusy] = useState(false);
    const [selectedItem, setSelectedItem] = useState<ChampionshipEvent>(null);

    useEffect(() => {
        loadData();
    }, [championshipId]);

    const loadData = () => {
        setIsBusy(true);
        apiFetch({
                     path: championshipEventsGetRoute(championshipId),
                     method: HttpMethod.GET,
                 }).then((r: ChampionshipEvent[]) => {
            setData(r ?? []);
            setIsBusy(false);
        });
    };

    const onDelete = (item: ChampionshipEvent) => {
    };
    const onEdit = (item: ChampionshipEvent) => {
    };

    const onNewSaved = () => {
        setIsBusy(false);
        setIsAdding(false);
        loadData();
    };

    const onCancelAdd = () => {
        setIsAdding(false);
    };

    const onAdd = () => {
        setIsAdding(true);
    }

    const headerTemplate = () => {
        return (
            <div className='flex justify-content-between'>
                <div>{__('Events', 'sim-league-toolkit')}</div>
                <div>
                    <button className='p-panel-header-icon p-link mr-2' onClick={onAdd}
                            title={__('Add a new Championship Event', 'sim-league-toolkit')}>
                        <span className='pi pi-plus'></span>
                    </button>
                </div>
            </div>
        )
    }

    const itemTemplate = (item: ChampionshipEvent) => {
        return <ChampionshipEventCard championshipEvent={item} key={item.id}
                                      onRequestEdit={onEdit}
                                      onRequestDelete={onDelete}/>;
    };

    return (
        <>
            {isAdding &&
                <NewChampionshipEventEditor championshipId={championshipId} gameId={gameId} onSaved={onNewSaved}
                                            onCancelled={onCancelAdd}/>
            }
            <BusyIndicator isBusy={isBusy} />
            <DataView value={data} itemTemplate={itemTemplate} layout='grid' header={headerTemplate()}
                      emptyMessage={__('No events have been created for this championship.', 'sim-league-toolkit')}
                      style={{marginRight: '1rem'}}/>
        </>
    );
};