import {__} from '@wordpress/i18n';
import apiFetch from '@wordpress/api-fetch';
import {useEffect, useState} from '@wordpress/element';

import {ConfirmDialog} from 'primereact/confirmdialog';
import {DataView} from 'primereact/dataview';

import {BusyIndicator} from '../shared/BusyIndicator';
import {HttpMethod} from '../shared/HttpMethod';
import {ScoringSetCard} from './ScoringSetCard';
import {ScoringSetEditor} from './ScoringSetEditor';
import {ScoringSet} from '../../types/ScoringSet';
import {scoringSetsGetRoute, scoringSetDeleteRoute} from '../../api/routes/scoringSetsApiRoutes';

export const ScoringSets = () => {
    const [isBusy, setIsBusy] = useState(false);
    const [data, setData] = useState<ScoringSet[]>([]);
    const [selectedItem, setSelectedItem] = useState<ScoringSet>(null);
    const [showDeleteConfirmation, setShowDeleteConfirmation] = useState(false);
    const [showEditor, setShowEditor] = useState(false);

    useEffect(() => {
        loadData();
    }, []);

    const loadData = () => {
        setIsBusy(true);
        apiFetch({
                     path: scoringSetsGetRoute(),
                     method: HttpMethod.GET,
                 }
        )
            .then((r: ScoringSet[]) => {
                setData(r ?? []);
                setIsBusy(false);
            });
    };

    const onAdd = () => {
        setShowEditor(true);
    };

    const onDelete = (item: ScoringSet) => {
        setSelectedItem(item);
        setShowDeleteConfirmation(true);
    };

    const onEdit = (item: ScoringSet) => {
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
                     path: scoringSetDeleteRoute(selectedItem.id),
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
                <div>{__('Scoring Sets', 'sim-league-toolkit')}</div>
                <div>
                    <button className='p-panel-header-icon p-link mr-2' onClick={onAdd}
                            title={__('Add a new Scoring Set', 'sim-league-toolkit')}>
                        <span className='pi pi-plus'></span>
                    </button>
                </div>
            </div>
        );
    };

    const itemTemplate = (item: ScoringSet) => {
        return <ScoringSetCard scoringSet={item} key={item.id} onRequestEdit={onEdit} onRequestDelete={onDelete}/>;
    };

    return (
        <>
            <BusyIndicator isBusy={isBusy}/>
            <h3>{__('Scoring Sets', 'sim-league-toolkit')}</h3>
            <p>
                {__('Sim League Toolkit allows you create re-usable Scoring Sets that can be applied to championships or individual events, saving you time and effort avoiding the need to create them multiple times.',
                    'sim-league-toolkit')}
            </p>
            <p>
                {__('A Scoring Set defines the points awarded to drivers based on the final position in a race. Optionally points can be awarded for setting the fastest lap, qualifying on pole or simply finishing the race',
                    'sim-league-toolkit')}
            </p>
            <p>
                {__('Sim League Toolkit provides a set of built-in scoring sets used in real world racing series, these cannot be deleted or changed',
                    'sim-league-toolkit')}
            </p>

            <DataView value={data} itemTemplate={itemTemplate} layout='grid' header={headerTemplate()}
                      emptyMessage={__('No Scoring Sets have been defined.', 'sim-league-toolkit')}
                      style={{marginRight: '1rem'}}/>
            {showEditor &&
                <ScoringSetEditor show={showEditor} onSaved={onEditorSaved} onCancelled={onEditorCancelled}
                                  scoringSetId={selectedItem?.id}/>
            }
            {selectedItem && showDeleteConfirmation &&
                <ConfirmDialog visible={showDeleteConfirmation} onHide={onCancelDelete} accept={onConfirmDelete}
                               reject={onCancelDelete}
                               header={__('Confirm Delete', 'sim-league-toolkit')}
                               icon='pi pi-exclamation-triangle'
                               acceptLabel={__('Yes', 'sim-league-toolkit)')}
                               rejectLabel={__('No', 'sim-league-toolkit')}
                               message={__('Deleting', 'sim-league-toolkit') + ' ' + selectedItem.name + ' ' + __(
                                   'will remove any links to championships or individual events!!.  Do you wish to delete ',
                                   'sim-league-toolkit') + ' ' + selectedItem.name + '?'}
                               style={{maxWidth: '50%'}}/>
            }
        </>
    );
};