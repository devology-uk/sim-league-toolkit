import {__} from '@wordpress/i18n';
import {useEffect, useState} from '@wordpress/element';
import apiFetch from '@wordpress/api-fetch';

import {ConfirmDialog} from 'primereact/confirmdialog';
import {DataView} from 'primereact/dataview';

import {BusySpinner} from '../shared/BusySpinner';
import {EventClassCard} from './EventClassCard';
import {EventClassEditor} from './EventClassEditor';
import {EventClass} from "./EventClass";
import {BusyIndicator} from "../shared/BusyIndicator";

export const EventClasses = () => {
    const [isBusy, setIsBusy] = useState(false);
    const [itemToDelete, setItemToDelete] = useState(null);
    const [tableData, setTableData] = useState([]);
    const [selectedItem, setSelectedItem] = useState<EventClass>(null);
    const [showDeleteConfirmation, setShowDeleteConfirmation] = useState(false);
    const [showEditor, setShowEditor] = useState(false);

    useEffect(() => {
        loadData();
    }, []);


    const loadData = () => {
        setIsBusy(true);
        apiFetch({path: '/sltk/v1/event-class'}).then((r: EventClass[]) => {
            setTableData(r ?? [])
            setIsBusy(false);
        });
    }

    const onAdd = () => {
        setShowEditor(true);
    }

    const onDelete = (item) => {
        setItemToDelete(item);
        setShowDeleteConfirmation(true);
    }

    const onEdit = (item) => {
        setSelectedItem(item);
        setShowEditor(true);
    }

    const onCancelDelete = () => {
        setShowDeleteConfirmation(false)
        setItemToDelete(null);
    }

    const onConfirmDelete = () => {
        setShowDeleteConfirmation(false)
        setIsBusy(true);
        apiFetch({
            path: 'sltk/v1/event-class/' + itemToDelete.id,
            method: 'DELETE'
        }).then(() => {
            loadData();
            setItemToDelete(null);
            setIsBusy(false);
        });
    }

    const onEditorCancelled = () => {
        setShowEditor(false);
        setSelectedItem(null);
    }

    const onEditorSaved = () => {
        setShowEditor(false);
        setSelectedItem(null);
        loadData();
    }

    const headerTemplate = () => {
        return (
            <div className='flex justify-content-between'>
                <div>{__('Event Classes', 'sim-league-toolkit')}</div>
                <div>
                    <button className='p-panel-header-icon p-link mr-2' onClick={onAdd}
                            title={__('Add a new Event Class', 'sim-league-toolkit')}>
                        <span className='pi pi-plus'></span>
                    </button>
                </div>
            </div>
        )
    }

    const itemTemplate = (item: EventClass) => {
        return <EventClassCard eventClass={item} key={item.id} onRequestEdit={onEdit}
                               onRequestDelete={onDelete}/>
    }

    return (
        <>
            <BusyIndicator isBusy={isBusy} />
            <h3>{__('Event Classes', 'sim-league-toolkit')}</h3>
            <p>
                {__('Sim League Toolkit allows you to create re-usable Event Classes that can be assigned to championships or individual events, saving you time and effort avoiding the need to create them multiple times.', 'sim-league-toolkit')}
            </p>
            <p>
                {__('An Event Class is a combination of a Car Class, Driver Category and optionally the single car that can be used in the class.', 'sim-league-toolkit')}
            </p>
            <p>
                {__('Sim League Toolkit provides a set of built-in event classes for each game, these cannot be deleted or changed.', 'sim-league-toolkit')}
            </p>

            <DataView value={tableData} itemTemplate={itemTemplate} layout='grid' header={headerTemplate()}
                      emptyMessage={__('No Event Classes have been defined.', 'sim-league-toolkit')}
                      style={{marginRight: '1rem'}}/>
            {showEditor &&
                <EventClassEditor show={showEditor} onSaved={onEditorSaved} onCancelled={onEditorCancelled}
                                  eventClassId={selectedItem?.id}/>
            }
            {itemToDelete && showDeleteConfirmation &&
                <ConfirmDialog visible={showDeleteConfirmation} onHide={onCancelDelete} accept={onConfirmDelete}
                               reject={onCancelDelete}
                               header={__('Confirm Delete', 'sim-league-toolkit')}
                               icon='pi pi-exclamation-triangle'
                               acceptLabel={__('Yes', 'sim-league-toolkit)')}
                               rejectLabel={__('No', 'sim-league-toolkit')}
                               message={__('Deleting', 'sim-league-toolkit') + ' ' + itemToDelete.name + ' ' + __('will remove it from all championships or individual events and any driver registrations for the class in those events will be removed!!.  Do you wish to delete ', 'sim-league-toolkit') + ' ' + itemToDelete.name + '?'}
                               style={{maxWidth: '50%'}}/>
            }
        </>
    )
}