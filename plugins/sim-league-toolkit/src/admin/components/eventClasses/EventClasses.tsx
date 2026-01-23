import {__} from '@wordpress/i18n';
import {useEffect, useState} from '@wordpress/element';
import apiFetch from '@wordpress/api-fetch';

import {ConfirmDialog} from 'primereact/confirmdialog';
import {DataView} from 'primereact/dataview';

import {BusyIndicator} from '../shared/BusyIndicator';
import {EventClass} from '../../types/EventClass';
import {EventClassCard} from './EventClassCard';
import {EventClassEditor} from './EventClassEditor';
import {eventClassesGetRoute, eventClassDeleteRoute} from '../../api/routes/eventClassesApiRoutes';
import {HttpMethod} from '../../enums/HttpMethod';

export const EventClasses = () => {
    const [isBusy, setIsBusy] = useState(false);
    const [data, setData] = useState<EventClass[]>([]);
    const [selectedItem, setSelectedItem] = useState<EventClass>(null);
    const [showDeleteConfirmation, setShowDeleteConfirmation] = useState(false);
    const [showEditor, setShowEditor] = useState(false);

    useEffect(() => {
        loadData();
    }, []);

    const loadData = () => {
        setIsBusy(true);
        apiFetch({
                     path: eventClassesGetRoute(),
                     method: HttpMethod.GET,
                 }).then((r: EventClass[]) => {
            setData(r ?? []);
            setIsBusy(false);
        });
    };

    const onAdd = () => {
        setShowEditor(true);
    };

    const onDelete = (item: EventClass) => {
        setSelectedItem(item);
        setShowDeleteConfirmation(true);
    };

    const onEdit = (item: EventClass) => {
        setSelectedItem(item);
        setShowEditor(true);
    };

    const onCancelDelete = () => {
        setShowDeleteConfirmation(false);
        setSelectedItem(null);
    };

    const onConfirmDelete = () => {
        setShowDeleteConfirmation(false);
        setIsBusy(true);
        apiFetch({
                     path: eventClassDeleteRoute(selectedItem.id),
                     method: HttpMethod.DELETE,
                 }).then(() => {
            loadData();
            setSelectedItem(null);
            setIsBusy(false);
        });
    };

    const onEditorCancelled = () => {
        setShowEditor(false);
        setSelectedItem(null);
    };

    const onEditorSaved = () => {
        setShowEditor(false);
        setSelectedItem(null);
        loadData();
    };

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
        );
    };

    const itemTemplate = (item: EventClass) => {
        return <EventClassCard eventClass={item} key={item.id} onRequestEdit={onEdit}
                               onRequestDelete={onDelete}/>;
    };

    return (
        <>
            <BusyIndicator isBusy={isBusy}/>
            <h3>{__('Event Classes', 'sim-league-toolkit')}</h3>
            <p>
                {__('Sim League Toolkit allows you to create re-usable Event Classes that can be assigned to championships or individual events, saving you time and effort avoiding the need to create them multiple times.',
                    'sim-league-toolkit')}
            </p>
            <p>
                {__('An Event Class is a combination of a Car Class, Driver Category and optionally the single car that can be used in the class.',
                    'sim-league-toolkit')}
            </p>
            <p>
                {__('Sim League Toolkit provides a set of built-in event classes for each game, these cannot be deleted or changed.',
                    'sim-league-toolkit')}
            </p>

            <DataView value={data} itemTemplate={itemTemplate} layout='grid' header={headerTemplate()}
                      emptyMessage={__('No Event Classes have been defined.', 'sim-league-toolkit')}
                      style={{marginRight: '1rem'}}/>
            {showEditor &&
                <EventClassEditor show={showEditor} onSaved={onEditorSaved} onCancelled={onEditorCancelled}
                                  eventClassId={selectedItem?.id}/>
            }
            {selectedItem && showDeleteConfirmation &&
                <ConfirmDialog visible={showDeleteConfirmation} onHide={onCancelDelete} accept={onConfirmDelete}
                               reject={onCancelDelete}
                               header={__('Confirm Delete', 'sim-league-toolkit')}
                               icon='pi pi-exclamation-triangle'
                               acceptLabel={__('Yes', 'sim-league-toolkit)')}
                               rejectLabel={__('No', 'sim-league-toolkit')}
                               message={__('Deleting', 'sim-league-toolkit') + ' ' + selectedItem.name + ' ' + __(
                                   'will remove it from all championships or individual events and any driver registrations for the class in those events will be removed!!.  Do you wish to delete ',
                                   'sim-league-toolkit') + ' ' + selectedItem.name + '?'}
                               style={{maxWidth: '50%'}}/>
            }
        </>
    );
};