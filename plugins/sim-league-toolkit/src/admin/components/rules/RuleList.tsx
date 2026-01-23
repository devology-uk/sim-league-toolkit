import {__} from '@wordpress/i18n';
import apiFetch from '@wordpress/api-fetch';
import {useEffect, useState} from '@wordpress/element';

import {Button} from 'primereact/button';
import {ConfirmDialog} from 'primereact/confirmdialog';
import {InputTextarea} from 'primereact/inputtextarea';
import {ListBox} from 'primereact/listbox';
import {Panel, PanelHeaderTemplateOptions} from 'primereact/panel';

import {BusyIndicator} from '../shared/BusyIndicator';
import {CancelButton} from '../shared/CancelButton';
import {HttpMethod} from '../../enums/HttpMethod';
import {RuleSet} from '../../types/RuleSet';
import {RuleSetRule} from '../../types/RuleSetRule';
import {rulesGetRoute, ruleDeleteRoute, rulePostRoute} from '../../api/routes/rulesApiRoutes';
import {SaveButton} from '../shared/SaveButton';
import {ValidationError} from '../shared/ValidationError';

interface RuleListProps {
    ruleSetId: number;
}

export const RuleList = ({ruleSetId}: RuleListProps) => {

    const [isAdding, setIsAdding] = useState(false);
    const [isEditing, setIsEditing] = useState(false);
    const [isBusy, setIsBusy] = useState(false);
    const [rules, setRules] = useState([]);
    const [ruleText, setRuleText] = useState('');
    const [selectedItem, setSelectedItem] = useState(null);
    const [showDeleteConfirmation, setShowDeleteConfirmation] = useState(false);
    const [validationErrors, setValidationErrors] = useState([]);

    useEffect(() => {
        loadData();
    }, []);

    const loadData = () => {
        setIsBusy(true);
        apiFetch({
                     path: rulesGetRoute(ruleSetId),
                     method: HttpMethod.GET,
                 }).then((r: RuleSet[]) => {
            setRules(r);
            setIsBusy(false);
            setIsAdding(false);
            setIsEditing(false);
            setSelectedItem(null);
        });
    };

    const onAdd = () => {
        setSelectedItem(null);
        setIsAdding(true);
    };

    const onCancelEdit = () => {
        setIsAdding(false);
        setIsEditing(false);
        setRuleText('');
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
                     path: ruleDeleteRoute(selectedItem.id),
                     method: HttpMethod.DELETE,
                 }).then(() => {
            loadData();
            setSelectedItem(null);
            setIsBusy(false);
        });
    };

    const onDelete = (item: RuleSetRule) => {
        setSelectedItem(item);
        setShowDeleteConfirmation(true);
    };

    const onEdit = (item: RuleSetRule) => {
        setSelectedItem(item);
        setRuleText(item.rule);
        setIsEditing(true);
    };

    const onSave = () => {
        if (!validate()) {
            return;
        }
        setIsBusy(true);

        const entity: RuleSetRule = {
            ruleSetId: ruleSetId,
            rule: ruleText,
        };

        if (isEditing) {
            entity.id = selectedItem.id;
        }

        apiFetch({
                     path: rulePostRoute(),
                     method: HttpMethod.POST,
                     data: entity,
                 }).then(() => {
            loadData();
            setRuleText('');
            setIsBusy(false);
            setIsAdding(false);
            setIsEditing(false);
        });
    };

    const validate = () => {
        const errors = [];

        if (!ruleText || ruleText.length < 15) {
            errors.push('ruleText');
        }

        setValidationErrors(errors);
        return errors.length === 0;
    };

    const headerTemplate = (options: PanelHeaderTemplateOptions) => {
        const className = `${options.className} justify-content-space-between`;

        return (
            <div className={className}>
                <div>
                    {__('Rules', 'sim-league-toolkit')}
                </div>
                <div>
                    <button className='p-panel-header-icon p-link mr-2' onClick={onAdd}>
                        <span className='pi pi-plus'></span>
                    </button>
                </div>
            </div>
        );
    };

    const itemTemplate = (item: RuleSetRule) => {
        return (
            <div key={item.id} className='flex flex-row'>
                <div className='flex-grow-1'>{item.rule}</div>
                <div className='flex row flex-grow-0 flex-shrink-1 justify-content-between' style={{flexBasis: '55px'}}>
                    <Button severity='success' size='small' onClick={() => onEdit(item)} icon='pi pi-pencil'/>
                    <Button severity='danger' size='small' onClick={() => onDelete(item)} icon='pi pi-trash'
                            className='ml-1'/>
                </div>
            </div>
        );
    };

    return (
        <>
            <BusyIndicator isBusy={isBusy}/>
            {!isAdding && !isEditing && (<Panel headerTemplate={headerTemplate}>
                <ListBox value={selectedItem} onChange={(e) => setSelectedItem(e.value)} options={rules}
                         optionLabel='rule'
                         itemTemplate={itemTemplate} className='w-full'
                         listStyle={{maxHeight: '250px', maxWidth: '550px'}}/>
            </Panel>)}
            {(isAdding || isEditing) && (<Panel header={__('Rule', 'sim-league-toolkit')}>
                <InputTextarea id='rule-set-rule' value={ruleText} onChange={(e) => setRuleText(e.target.value)}
                               placeholder={__('Enter the text for the rule.', 'sim-league-toolkit')}
                               rows={10} cols={50}/>
                <ValidationError
                    message={__('The text for the rule with at least 15 characters is required.', 'sim-league-toolkit')}
                    show={validationErrors.includes('description')}/>
                <br/>
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
                               message={__('Are you sure you want to delete the rule: ',
                                           'sim-league-toolkit') + ' "' + selectedItem.rule + '"? '}
                               style={{maxWidth: '50%'}}/>
            }
        </>
    );
};