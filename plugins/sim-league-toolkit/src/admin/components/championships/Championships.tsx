import {__} from '@wordpress/i18n';
import {useState} from '@wordpress/element';

import {ConfirmDialog} from 'primereact/confirmdialog';
import {DataView} from 'primereact/dataview';

import {BusyIndicator} from '../shared/BusyIndicator';
import {Championship} from '../../types/Championship';
import {ChampionshipCard} from './ChampionshipCard';
import {ChampionshipEditor} from './ChampionshipEditor';
import {NewChampionshipEditor} from './NewChampionshipEditor';
import {useChampionships} from '../../hooks/useChampionships';

export const Championships = () => {
    const {championships, deleteChampionship, isLoading, refresh} = useChampionships();

    const [isAdding, setIsAdding] = useState(false);
    const [isEditing, setIsEditing] = useState(false);
    const [selectedItem, setSelectedItem] = useState<Championship>();
    const [showDeleteConfirmation, setShowDeleteConfirmation] = useState(false);

    const onAdd = () => {
        setIsAdding(true);
        setIsEditing(false);
    };

    const onCancelDelete = () => {
        setShowDeleteConfirmation(false);
        setSelectedItem(null);
    };

    const onConfirmDelete = async () => {
        setShowDeleteConfirmation(false);
        await deleteChampionship(selectedItem.id);
        setSelectedItem(null);
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

    const onEditorSaved = async () => {
        setIsEditing(false);
        setIsAdding(false);
        setSelectedItem(null);
        await refresh();
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
                <BusyIndicator isBusy={isLoading}/>
                <h3>{__('Championships', 'sim-league-toolkit')}</h3>
                <p>
                    {__('The championships you have created are displayed below.', 'sim-league-toolkit')}
                </p>

                <DataView value={championships} itemTemplate={itemTemplate} layout='grid' header={headerTemplate()}
                          emptyMessage={__('No Championships have been defined.', 'sim-league-toolkit')}
                          style={{marginRight: '1rem'}}/>
            </>
            }
            {isAdding && <NewChampionshipEditor onSaved={onEditorSaved} onCancelled={onEditorCancelled}/>}
            {isEditing && <ChampionshipEditor onSaved={onEditorSaved} onCancelled={onEditorCancelled}
                                              championship={selectedItem}/>}
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