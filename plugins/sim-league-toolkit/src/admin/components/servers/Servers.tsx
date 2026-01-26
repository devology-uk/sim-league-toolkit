import {__} from '@wordpress/i18n';
import {useState} from '@wordpress/element';

import {ConfirmDialog} from 'primereact/confirmdialog';
import {DataView} from 'primereact/dataview';

import {BusyIndicator} from '../shared/BusyIndicator';
import {Server} from '../../types/Server';
import {ServerCard} from './ServerCard';
import {ServerEditor} from './ServerEditor';
import {useServers} from '../../hooks/useServers';

export const Servers = () => {
    const {deleteServer, isLoading, servers} = useServers();

    const [selectedItem, setSelectedItem] = useState<Server>(null);
    const [showDeleteConfirmation, setShowDeleteConfirmation] = useState(false);
    const [showEditor, setShowEditor] = useState(false);

    const onAdd = () => {
        setShowEditor(true);
    };

    const onDelete = (item: Server) => {
        setSelectedItem(item);
        setShowDeleteConfirmation(true);
    };

    const onEdit = (item: Server) => {
        console.log(item);
        setSelectedItem(item);
        setShowEditor(true);
    };

    const onCancelDelete = () => {
        setShowDeleteConfirmation(false);
        setSelectedItem(null);
    };

    const onConfirmDelete = async () => {
        setShowDeleteConfirmation(false);

        await deleteServer(selectedItem.id);

        setSelectedItem(null);
    };

    const onEditorCancelled = () => {
        setShowEditor(false);
        setSelectedItem(null);
    };

    const onEditorSaved = () => {
        setShowEditor(false);
        setSelectedItem(null);
    };

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
        );
    };

    const itemTemplate = (item: Server) => {
        return <ServerCard server={item} key={item.id} onRequestEdit={onEdit} onRequestDelete={onDelete}/>;
    };

    return (
        <>
            <BusyIndicator isBusy={isLoading}/>
            <h3>{__('Servers', 'sim-league-toolkit')}</h3>
            <p>
                {__('Sim League Toolkit allows you create re-usable Servers with settings configured that can be applied to championship events or individual events, saving you time and effort avoiding the need to enter the same settings multiple times.',
                    'sim-league-toolkit')}
            </p>
            <p>
                {__('A Server represents a game server where you host the events for your league, when you create a championship event or individual event you have the option to select the server that will host the event.',
                    'sim-league-toolkit')}
            </p>
            <p>
                {__('Information to help members access the server for an event can be displayed in your web site using appropriate Gutenburg Blocks.',
                    'sim-league-toolkit')}
            </p>

            <DataView value={servers} itemTemplate={itemTemplate} layout='grid' header={headerTemplate()}
                      emptyMessage={__('No Servers have been defined.', 'sim-league-toolkit')}
                      style={{marginRight: '1rem'}}/>
            {showEditor &&
                <ServerEditor show={showEditor} onSaved={onEditorSaved} onCancelled={onEditorCancelled}
                              serverId={selectedItem?.id}/>
            }
            {selectedItem && showDeleteConfirmation &&
                <ConfirmDialog visible={showDeleteConfirmation} onHide={onCancelDelete} accept={onConfirmDelete}
                               reject={onCancelDelete}
                               header={__('Confirm Delete', 'sim-league-toolkit')}
                               icon='pi pi-exclamation-triangle'
                               acceptLabel={__('Yes', 'sim-league-toolkit)')}
                               rejectLabel={__('No', 'sim-league-toolkit')}
                               message={__('Deleting', 'sim-league-toolkit') + ' ' + selectedItem.name + ' ' + __(
                                   'will remove any links to events!!.  Do you wish to delete ',
                                   'sim-league-toolkit') + ' ' + selectedItem.name + '?'}
                               style={{maxWidth: '50%'}}/>
            }
        </>
    );
};