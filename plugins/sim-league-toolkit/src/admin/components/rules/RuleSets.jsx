import {__} from '@wordpress/i18n';
import {useEffect, useState} from '@wordpress/element';
import apiFetch from '@wordpress/api-fetch';

import {ConfirmDialog} from 'primereact/confirmdialog';
import {DataView} from 'primereact/dataview';

import {BusySpinner} from '../BusySpinner';
import {RuleSetEditor} from './RuleSetEditor';
import {RuleSetCard} from './RuleSetCard';

export const RuleSets = () => {

    const [isBusy, setIsBusy] = useState(false);
    const [itemToDelete, setItemToDelete] = useState(null);
    const [tableData, setTableData] = useState([]);
    const [selectedItem, setSelectedItem] = useState();
    const [showDeleteConfirmation, setShowDeleteConfirmation] = useState(false);
    const [showEditor, setShowEditor] = useState(false);

    useEffect(() => {
        loadData();
    }, []);


    const loadData = () => {
        setIsBusy(true);
        apiFetch({path: '/sltk/v1/rule-set'}).then((r) => {
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
            path: 'sltk/v1/rule-set/' + itemToDelete.id,
            method: 'DELETE'
        }).then(() => {
            loadData();
            setItemToDelete(null);
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
                <div>{__('Rule Sets', 'sim-league-toolkit')}</div>
                <div>
                    <button className='p-panel-header-icon p-link mr-2' onClick={onAdd}
                            title={__('Add a new  Rule Set', 'sim-league-toolkit')}>
                        <span className='pi pi-plus'></span>
                    </button>
                </div>
            </div>
        )
    }

    const itemTemplate = (item) => {
        return <RuleSetCard ruleSet={item.data} key={item.id}  onRequestEdit={(item) => onEdit(item)} onRequestDelete={(item) => onDelete(item)} />
    }

    return (
        <>
            <h3>{__('Rule Sets', 'sim-league-toolkit')}</h3>
            <p>
                {__('Sim League Toolkit allows you create re-usable Rule Sets that can be applied to championships or individual events, saving you time and effort avoiding the need to write them multiple times.', 'sim-league-toolkit')}
            </p>
            <p>
                {__('A Rule Set is a collection of Rules, when you create a championship or individual event you have the option to select a rule set that applies in addition to any community rules you have defined.', 'sim-league-toolkit')}
            </p>
            <p>
                {__('When members sign up for championships or individual events they are required to accept each set of rules.', 'sim-league-toolkit')}
            </p>

            <DataView value={tableData} itemTemplate={itemTemplate} layout='grid' header={headerTemplate()}
                      emptyMessage={__('No Result Sets have been defined.', 'sim-league-toolkit')} style={{marginRight:'1rem'}}/>
            {showEditor &&
                <RuleSetEditor show={showEditor} onSaved={onEditorSaved} onCancelled={onEditorCancelled}
                               ruleSetId={selectedItem?.id}/>
            }
            {itemToDelete && showDeleteConfirmation &&
                <ConfirmDialog visible={showDeleteConfirmation} onHide={onCancelDelete} accept={onConfirmDelete}
                               reject={onCancelDelete}
                               header={__('Confirm Delete', 'sim-league-toolkit')}
                               icon='pi pi-exclamation-triangle'
                               acceptLabel={__('Yes', 'racket-club)')} rejectLabel={__('No', 'racket-club')}
                               message={__('Deleting', 'racket-club') + ' ' + itemToDelete.name + ' ' + __('will remove any links to championships or individual events!!.  Do you wish to delete ', 'sim-league-toolkit') + ' ' + itemToDelete.name + '?'}
                style={{maxWidth: '50%'}}/>
            }
            <BusySpinner isActive={isBusy}/>
        </>
    )
}