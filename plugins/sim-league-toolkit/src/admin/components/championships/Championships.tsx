import {__} from '@wordpress/i18n';
import {useEffect, useState} from '@wordpress/element';
import apiFetch from '@wordpress/api-fetch';

import {ConfirmDialog} from 'primereact/confirmdialog';
import {DataView} from 'primereact/dataview';

import {BusyIndicator} from '../shared/BusyIndicator';
import {Championship} from '../../types/Championship';
import {ChampionshipCard} from './ChampionshipCard';
import {ChampionshipEditor} from './ChampionshipEditor';
import {HttpMethod} from '../../enums/HttpMethod';
import {NewChampionshipEditor} from './NewChampionshipEditor';
import {championshipDeleteRoute, championshipsGetRoute} from '../../api/routes/championshipApiRoutes';

export const Championships = () => {
    const [isAdding, setIsAdding] = useState(false);
    const [isBusy, setIsBusy] = useState(false);
    const [isEditing, setIsEditing] = useState(false);
    const [data, setData] = useState<Championship[]>([]);
    const [selectedItem, setSelectedItem] = useState<Championship>();
    const [showDeleteConfirmation, setShowDeleteConfirmation] = useState(false);

    useEffect(() => {
        loadData();
    }, []);

    const loadData = () => {
        setIsBusy(true);
        apiFetch({
                     path: championshipsGetRoute(),
                     method: HttpMethod.GET,
                 }).then((r: Championship[]) => {
            setData(r ?? []);
            setIsBusy(false);
        });
    };

    const onAdd = () => {
        setIsAdding(true);
        setIsEditing(false);
    };

    const onCancelDelete = () => {
        setShowDeleteConfirmation(false);
        setSelectedItem(null);
    };

    const onConfirmDelete = () => {
        setShowDeleteConfirmation(false);
        setIsBusy(true);
        apiFetch({
                     path: championshipDeleteRoute(selectedItem.id),
                     method: HttpMethod.DELETE,
                 }).then(() => {
            loadData();
            setSelectedItem(null);
            setIsBusy(false);
        });
    };

    const onDelete = (item: Championship) => {
        setSelectedItem(item);
        setShowDeleteConfirmation(true);
    };

    const onEdit = (item: Championship) => {
        setIsEditing(true);
        setIsAdding(false);
        setSelectedItem(item);
    };

    const onEditorCancelled = () => {
        setIsEditing(false);
        setIsAdding(false);
        setSelectedItem(null);
    };

    const onEditorSaved = () => {
        setIsEditing(false);
        setIsAdding(false);
        setSelectedItem(null);
        loadData();
    };

    const headerTemplate = () => {
        return (
            <div className='flex justify-content-between'>
                <div>{__('Championships', 'sim-league-toolkit')}</div>
                <div>
                    <button className='p-panel-header-icon p-link mr-2' onClick={onAdd}
                            title={__('Add a new Championship', 'sim-league-toolkit')}>
                        <span className='pi pi-plus'></span>
                    </button>
                </div>
            </div>
        );
    };

    const itemTemplate = (item: Championship) => {
        return <ChampionshipCard championship={item} key={item.id} onRequestEdit={onEdit}
                                 onRequestDelete={onDelete}/>;
    };

    return (
        <>
            {!isAdding && !isEditing && <>
                <BusyIndicator isBusy={isBusy}/>
                <h3>{__('Championships', 'sim-league-toolkit')}</h3>
                <p>
                    {__('The championships you have created are displayed below.', 'sim-league-toolkit')}
                </p>

                <DataView value={data} itemTemplate={itemTemplate} layout='grid' header={headerTemplate()}
                          emptyMessage={__('No Championships have been defined.', 'sim-league-toolkit')}
                          style={{marginRight: '1rem'}}/>
            </>
            }
            {isAdding && <NewChampionshipEditor onSaved={onEditorSaved} onCancelled={onEditorCancelled}/>}
            {isEditing && <ChampionshipEditor onSaved={onEditorSaved} onCancelled={onEditorCancelled}
                                              championshipId={selectedItem?.id}/>}
            {selectedItem && showDeleteConfirmation &&
                <ConfirmDialog visible={showDeleteConfirmation} onHide={onCancelDelete} accept={onConfirmDelete}
                               reject={onCancelDelete}
                               header={__('Confirm Delete', 'sim-league-toolkit')}
                               icon='pi pi-exclamation-triangle'
                               acceptLabel={__('Yes', 'sim-league-toolkit)')}
                               rejectLabel={__('No', 'sim-league-toolkit')}
                               message={__('Deleting', 'sim-league-toolkit') + ' ' + selectedItem.name + ' ' + __(
                                   'will remove' +
                                   ' it including all of the events, results, standings and related data!!.  Do you wish to delete ',
                                   'sim-league-toolkit') + ' ' + selectedItem.name + '?'}
                               style={{maxWidth: '50%'}}/>
            }
        </>
    );
};