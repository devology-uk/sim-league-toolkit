import {__} from '@wordpress/i18n';
import {useEffect, useState} from '@wordpress/element';
import apiFetch from '@wordpress/api-fetch';

import {ConfirmDialog} from 'primereact/confirmdialog';
import {DataView} from 'primereact/dataview';

import {BusyIndicator} from "../shared/BusyIndicator";
import {Server} from "./Server";
import {ServerEditor} from './ServerEditor';
import {ServerCard} from './ServerCard';

export const Servers = () => {
    const [isBusy, setIsBusy] = useState(false);
    const [itemToDelete, setItemToDelete] = useState<Server>(null);
    const [data, setData] = useState<Server[]>([]);
    const [selectedItem, setSelectedItem] = useState<Server>(null);
    const [showDeleteConfirmation, setShowDeleteConfirmation] = useState(false);
    const [showEditor, setShowEditor] = useState(false);

    useEffect(() => {
        loadData();
    }, []);


    const loadData = () => {
        setIsBusy(true);
        apiFetch({path: '/sltk/v1/server'}).then((r: Server[]) => {
            setData(r ?? [])
            setIsBusy(false);
        });
    }

    const onAdd = () => {
        setShowEditor(true);
    }

    const onDelete = (item: Server) => {
        setItemToDelete(item);
        setShowDeleteConfirmation(true);
    }

    const onEdit = (item: Server) => {
        console.log(item);
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
            path: 'sltk/v1/server/' + itemToDelete.id,
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
                <div>{__('Servers', 'sim-league-toolkit')}</div>
                <div>
                    <button className='p-panel-header-icon p-link mr-2' onClick={onAdd}
                            title={__('Add a new Server', 'sim-league-toolkit')}>
                        <span className='pi pi-plus'></span>
                    </button>
                </div>
            </div>
        )
    }

    const itemTemplate = (item: Server) => {
        return <ServerCard server={item} key={item.id} onRequestEdit={onEdit} onRequestDelete={onDelete}/>
    }

    return (
        <>
            <BusyIndicator isBusy={isBusy} />
            <h3>{__('Servers', 'sim-league-toolkit')}</h3>
            <p>
                {__('Sim League Toolkit allows you create re-usable Servers with settings configured that can be applied to championship events or individual events, saving you time and effort avoiding the need to enter the same settings multiple times.', 'sim-league-toolkit')}
            </p>
            <p>
                {__('A Server represents a game server where you host the events for your league, when you create a championship event or individual event you have the option to select the server that will host the event.', 'sim-league-toolkit')}
            </p>
            <p>
                {__('Information to help members access the server for an event can be displayed in your web site using appropriate Gutenburg Blocks.', 'sim-league-toolkit')}
            </p>

            <DataView value={data} itemTemplate={itemTemplate} layout='grid' header={headerTemplate()}
                      emptyMessage={__('No Servers have been defined.', 'sim-league-toolkit')}
                      style={{marginRight: '1rem'}}/>
            {showEditor &&
                <ServerEditor show={showEditor} onSaved={onEditorSaved} onCancelled={onEditorCancelled}
                              serverId={selectedItem?.id}/>
            }
            {itemToDelete && showDeleteConfirmation &&
                <ConfirmDialog visible={showDeleteConfirmation} onHide={onCancelDelete} accept={onConfirmDelete}
                               reject={onCancelDelete}
                               header={__('Confirm Delete', 'sim-league-toolkit')}
                               icon='pi pi-exclamation-triangle'
                               acceptLabel={__('Yes', 'sim-league-toolkit)')}
                               rejectLabel={__('No', 'sim-league-toolkit')}
                               message={__('Deleting', 'sim-league-toolkit') + ' ' + itemToDelete.name + ' ' + __('will remove any links to events!!.  Do you wish to delete ', 'sim-league-toolkit') + ' ' + itemToDelete.name + '?'}
                               style={{maxWidth: '50%'}}/>
            }
        </>
    )
}