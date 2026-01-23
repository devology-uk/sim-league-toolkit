import {__} from '@wordpress/i18n';
import apiFetch from '@wordpress/api-fetch';
import {useEffect, useState} from '@wordpress/element';

import {Button} from 'primereact/button';
import {Column} from 'primereact/column';
import {ConfirmDialog} from 'primereact/confirmdialog';
import {DataTable} from 'primereact/datatable';
import {InputNumber} from 'primereact/inputnumber';
import {Panel, PanelHeaderTemplateOptions} from 'primereact/panel';

import {BusyIndicator} from '../shared/BusyIndicator';
import {CancelButton} from '../shared/CancelButton';
import {HttpMethod} from '../../enums/HttpMethod';
import {SaveButton} from '../shared/SaveButton';
import {scoresGetRoute, scoreDeleteRoute, scorePostRoute} from '../../api/routes/scoringSetsApiRoutes';
import {ScoringSetScore} from '../../types/ScoringSetScore';
import {ValidationError} from '../shared/ValidationError';

interface ScoreListProps {
    scoringSetId: number;
}

export const ScoreList = ({scoringSetId}: ScoreListProps) => {
    const [isAdding, setIsAdding] = useState(false);
    const [isEditing, setIsEditing] = useState(false);
    const [isBusy, setIsBusy] = useState(false);
    const [points, setPoints] = useState(25);
    const [position, setPosition] = useState(1);
    const [data, setData] = useState<ScoringSetScore[]>([]);
    const [selectedItem, setSelectedItem] = useState<ScoringSetScore>(null);
    const [showDeleteConfirmation, setShowDeleteConfirmation] = useState(false);
    const [validationErrors, setValidationErrors] = useState<string[]>([]);

    useEffect(() => {
        loadData();
    }, []);

    const loadData = () => {
        setIsBusy(true);
        apiFetch({
                     path: scoresGetRoute(scoringSetId),
                     method: HttpMethod.GET,
                 }).then((r: ScoringSetScore[]) => {
            setData(r);
            setIsBusy(false);
            setIsAdding(false);
            setIsEditing(false);
            setSelectedItem(null);
        });
    };

    const onAdd = () => {

        if (data && data.length > 0) {
            let lastPosition = 0;
            let lastPoints = 999;

            data.forEach(sc => {
                lastPosition = Math.max(lastPosition, sc.position);
                lastPoints = Math.min(lastPoints, sc.points);
            });

            setPosition(lastPosition + 1);
            setPoints(Math.max(0, lastPoints - 1));
        } else {
            setPoints(25);
            setPosition(1);
        }

        setSelectedItem(null);
        setIsAdding(true);
    };

    const onCancelEdit = () => {
        setIsAdding(false);
        setIsEditing(false);
        setSelectedItem(null);
    };

    const onCancelDelete = () => {
        setShowDeleteConfirmation(false);
        setSelectedItem(null);
    };

    const onConfirmDelete = () => {
        setShowDeleteConfirmation(false);
        setIsBusy(true);
        apiFetch({
                     path: scoreDeleteRoute(selectedItem.id),
                     method: HttpMethod.DELETE,
                 }).then(() => {
            loadData();
            setSelectedItem(null);
            setIsBusy(false);
        });
    };

    const onDelete = (item: ScoringSetScore) => {
        setSelectedItem(item);
        setShowDeleteConfirmation(true);
    };

    const onEdit = (item: ScoringSetScore) => {
        setSelectedItem(item);
        setPoints(item.points);
        setPosition(item.position);
        setIsEditing(true);
    };

    const onSave = () => {
        if (!validate()) {
            return;
        }
        setIsBusy(true);

        const entity: ScoringSetScore = {
            scoringSetId: scoringSetId,
            points: points,
            position: position,
        };

        if (isEditing) {
            entity.id = selectedItem.id;
        }

        apiFetch({
                     path: scorePostRoute(),
                     method: HttpMethod.POST,
                     data: entity,
                 }).then(() => {
            loadData();
            setPoints(0);
            setPosition(0);
            setIsBusy(false);
            setIsAdding(false);
            setIsEditing(false);
        });
    };

    const validate = () => {
        const errors: string[] = [];

        if (points < 1) {
            errors.push('points');
        }

        if (position < 1) {
            errors.push('position');
        }

        setValidationErrors(errors);
        return errors.length === 0;
    };

    const actionTemplate = (item: ScoringSetScore) => {
        return (
            <div className='flex flex-row'>
                <Button severity='success' size='small' onClick={() => onEdit(item)} icon='pi pi-pencil'/>
                <Button severity='danger' size='small' onClick={() => onDelete(item)} icon='pi pi-trash'
                        className='ml-1'/>
            </div>
        );
    };

    const headerTemplate = (options: PanelHeaderTemplateOptions) => {
        const className = `${options.className} justify-content-space-between`;

        return (
            <div className={className}>
                <div>
                    {__('Scores', 'sim-league-toolkit')}
                </div>
                <div>
                    <button className='p-panel-header-icon p-link mr-2' onClick={onAdd}>
                        <span className='pi pi-plus'></span>
                    </button>
                </div>
            </div>
        );
    };

    return (
        <>
            <BusyIndicator isBusy={isBusy}/>
            {!isAdding && !isEditing && (
                <Panel headerTemplate={headerTemplate}>
                    <DataTable value={data} size='small'
                               emptyMessage={__('No scores found', 'sim-league-toolkit')}>
                        <Column field='position' header='Position'/>
                        <Column field='points' header='Points'/>
                        <Column header='' body={actionTemplate}/>
                    </DataTable>
                </Panel>)}
            {(isAdding || isEditing) && (<Panel header={__('Score', 'sim-league-toolkit')}>

                <div className='flex flex-column align-items-stretch gap-2'>
                    <label htmlFor='score-position'>{__('Position', 'sim-league-toolkit')}</label>
                    <InputNumber id='score-position' value={position} onChange={(e) => setPosition(e.value)}
                                 placeholder={__('Enter the position.', 'sim-league-toolkit')}
                                 min={1} max={999} autoFocus/>
                    <ValidationError
                        message={__('The position for the score is required.', 'sim-league-toolkit')}
                        show={validationErrors.includes('position')}/>
                    <br/>
                    <label htmlFor='score-points'>{__('Points', 'sim-league-toolkit')}</label>
                    <InputNumber id='score-points' value={points} onChange={(e) => setPoints(e.value)}
                                 placeholder={__('Enter the points.', 'sim-league-toolkit')}
                                 min={1} max={999}/>
                    <ValidationError
                        message={__('The points for the score is required.', 'sim-league-toolkit')}
                        show={validationErrors.includes('points')}/>
                    <br/>
                </div>
                <SaveButton onClick={onSave} disabled={isBusy}/>
                <CancelButton onCancel={onCancelEdit} disabled={isBusy}/>
            </Panel>)}
            {selectedItem && showDeleteConfirmation &&
                <ConfirmDialog visible={showDeleteConfirmation} onHide={onCancelDelete} accept={onConfirmDelete}
                               reject={onCancelDelete}
                               header={__('Confirm Delete', 'sim-league-toolkit')}
                               icon='pi pi-exclamation-triangle'
                               acceptLabel={__('Yes', 'sim-league-toolkit)')}
                               rejectLabel={__('No', 'sim-league-toolkit')}
                               message={__('Are you sure you want to delete the score: ',
                                           'sim-league-toolkit') + ' "' + selectedItem.position + '=' + selectedItem.points + '"? '}
                               style={{maxWidth: '50%'}}/>
            }
        </>
    );
};