import {__} from '@wordpress/i18n';
import {useState, useEffect} from '@wordpress/element';
import apiFetch from '@wordpress/api-fetch';

import {ConfirmDialog} from 'primereact/confirmdialog';
import {DataView} from 'primereact/dataview';

import {BusyIndicator} from '../shared/BusyIndicator';
import {ChampionshipEvent} from '../../types/ChampionshipEvent';
import {ChampionshipEventCard} from './ChampionshipEventCard';
import {HttpMethod} from '../../enums/HttpMethod';
import {NewChampionshipEventEditor} from './NewChampionshipEventEditor';
import {championshipEventsEndpoint, championshipEventEndpoint} from '../../api/endpoints/championshipEventsApiRoutes';
import {ChampionshipEventEditor} from './ChampionshipEventEditor';

interface ChampionshipEventsProps {
    championshipId: number,
    gameId: number,
}

export const ChampionshipEvents = ({championshipId, gameId}: ChampionshipEventsProps) => {
    const [data, setData] = useState<ChampionshipEvent[]>([]);
    const [isAdding, setIsAdding] = useState(false);
    const [isEditing, setIsEditing] = useState(false);
    const [isBusy, setIsBusy] = useState(false);
    const [selectedItem, setSelectedItem] = useState<ChampionshipEvent>(null);
    const [showDeleteConfirmation, setShowDeleteConfirmation] = useState(false);

    useEffect(() => {
        loadData();
    }, [championshipId]);

    const loadData = () => {
        setIsBusy(true);
        apiFetch({
                     path: championshipEventsEndpoint(championshipId),
                     method: HttpMethod.GET,
                 }).then((r: ChampionshipEvent[]) => {
            setData(r ?? []);
            setIsBusy(false);
        });
    };

    const onAdd = () => {
        setIsAdding(true);
    };

    const onCancelAdd = () => {
        setIsAdding(false);
    };

    const onCancelDelete = () => {
        setShowDeleteConfirmation(false);
        setSelectedItem(null);
    };

    const onCancelEdit = () => {
        setIsEditing(false);
        setSelectedItem(null);
    };

    const onConfirmDelete = () => {
        setShowDeleteConfirmation(false);
        setIsBusy(true);
        apiFetch({
                     path: championshipEventEndpoint(selectedItem.id),
                     method: HttpMethod.DELETE,
                 }).then(() => {
            loadData();
            setSelectedItem(null);
            setIsBusy(false);
        });
    };

    const onDelete = (item: ChampionshipEvent) => {
        setSelectedItem(item);
        setShowDeleteConfirmation(true);
    };

    const onEdit = (item: ChampionshipEvent) => {
        setSelectedItem(item);
        setIsEditing(true);
    };

    const onEditSaved = () => {
        setIsEditing(false);
        setSelectedItem(null);
        loadData();
    };

    const onNewSaved = () => {
        setIsBusy(false);
        setIsAdding(false);
        loadData();
    };

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
        );
    };

    const itemTemplate = (item: ChampionshipEvent) => {
        return <ChampionshipEventCard championshipEvent={item} key={item.id}
                                      onRequestEdit={onEdit}
                                      onRequestDelete={onDelete}/>;
    };

    return (
        <>

            <BusyIndicator isBusy={isBusy}/>
            <DataView value={data} itemTemplate={itemTemplate} layout='grid' header={headerTemplate()}
                      emptyMessage={__('No events have been created for this championship.', 'sim-league-toolkit')}
                      style={{marginRight: '1rem'}}/>
            {isAdding &&
                <NewChampionshipEventEditor championshipId={championshipId} gameId={gameId} onSaved={onNewSaved}
                                            onCancelled={onCancelAdd}/>
            }
            {selectedItem && isEditing &&
                <ChampionshipEventEditor championshipEvent={selectedItem} gameId={gameId} onSaved={onEditSaved}
                                         onCancelled={onCancelEdit}/>
            }
            {selectedItem && showDeleteConfirmation &&
                <ConfirmDialog visible={showDeleteConfirmation} onHide={onCancelDelete} accept={onConfirmDelete}
                               reject={onCancelDelete}
                               header={__('Confirm Delete', 'sim-league-toolkit')}
                               icon='pi pi-exclamation-triangle'
                               acceptLabel={__('Yes', 'sim-league-toolkit)')}
                               rejectLabel={__('No', 'sim-league-toolkit')}
                               message={__('Deleting', 'sim-league-toolkit') + ' ' + selectedItem.name + ' ' + __(
                                   'will remove it including all of the sessions, results, and related data!!.  Do you wish to delete ',
                                   'sim-league-toolkit') + ' ' + selectedItem.name + '?'}
                               style={{maxWidth: '50%'}}/>
            }
        </>
    );
};