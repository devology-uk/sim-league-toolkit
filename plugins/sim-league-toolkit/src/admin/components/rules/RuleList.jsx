import {__} from '@wordpress/i18n';
import {useEffect, useState} from '@wordpress/element';
import apiFetch from '@wordpress/api-fetch';

import {InputTextarea} from 'primereact/inputtextarea';
import {ListBox} from 'primereact/listbox';
import {Panel} from 'primereact/panel';

import {SaveButton} from '../shared/SaveButton';
import {ValidationError} from '../shared/ValidationError';
import {Button} from 'primereact/button';
import {BusySpinner} from '../shared/BusySpinner';
import {ConfirmDialog} from 'primereact/confirmdialog';
import {CancelButton} from '../shared/CancelButton';

export const RuleList = ({ruleSetId}) => {

    const [isAdding, setIsAdding] = useState(false);
    const [isEditing, setIsEditing] = useState(false);
    const [isBusy, setIsBusy] = useState(false);
    const [rules, setRules] = useState([]);
    const [ruleText, setRuleText] = useState('');
    const [selectedRule, setSelectedRule] = useState(null);
    const [showDeleteConfirmation, setShowDeleteConfirmation] = useState(false);
    const [validationErrors, setValidationErrors] = useState([]);

    useEffect(() => {
        loadData();
    }, [])


    const loadData = () => {
        setIsBusy(true);
        apiFetch({
            path: `/sltk/v1/rule-set-rule/${ruleSetId}`,
            method: 'GET',
        }).then((r) => {
            setRules(r);
            setIsBusy(false);
            setIsAdding(false);
            setIsEditing(false);
            setSelectedRule(null);
        });
    }

    const onAdd = () => {
        setSelectedRule(null);
        setIsAdding(true);
    }

    const onCancelEdit = () => {
        setIsAdding(false);
        setIsEditing(false);
        setRuleText('');
        setSelectedRule(null);
    }

    const onCancelDelete = () => {
        setShowDeleteConfirmation(false);
        setSelectedRule(null);
    }

    const onConfirmDelete = () => {
        setShowDeleteConfirmation(false);
        setIsBusy(true);
        apiFetch({
            path: 'sltk/v1/rule-set-rule/' + selectedRule.id,
            method: 'DELETE'
        }).then(() => {
            loadData();
            setSelectedRule(null);
            setIsBusy(false);
        });
    }

    const onDelete = (rule) => {
        setSelectedRule(rule);
        setShowDeleteConfirmation(true);
    }

    const onEdit = (rule) => {
        setSelectedRule(rule);
        setRuleText(rule.rule);
        setIsEditing(true);
    }

    const onSave = () => {
        if (!validate()) {
            return;
        }
        setIsBusy(true);

        const rule = {
            ruleSetId: ruleSetId,
            rule: ruleText,
        }

        if (isEditing) {
            rule.id = selectedRule.id;
        }

        apiFetch({
            path: '/sltk/v1/rule-set-rule',
            method: 'POST',
            data: rule,
        }).then(() => {
            loadData();
            setRuleText('');
            setIsBusy(false);
            setIsAdding(false);
            setIsEditing(false);
        });
    }

    const validate = () => {
        const errors = [];

        if (!ruleText || ruleText.length < 15) {
            errors.push('ruleText');
        }

        setValidationErrors(errors);
        return errors.length === 0;
    }

    const headerTemplate = (options) => {
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

    const itemTemplate = (item) => {
        return (
            <div key={item.id} className='flex flex-row'>
                <div className='flex-grow-1'>{item.rule}</div>
                <div className='flex row flex-grow-0 flex-shrink-1 justify-content-between' style={{flexBasis: '55px'}}>
                    <Button severity='success' size='small' onClick={() => onEdit(item)} icon='pi pi-pencil'/>
                    <Button severity='danger' size='small' onClick={() => onDelete(item)} icon='pi pi-trash'
                            className='ml-1'/>
                </div>
            </div>
        )
    }

    return (
        <>
            {!isAdding && !isEditing && (<Panel headerTemplate={headerTemplate}>
                <ListBox value={selectedRule} onChange={(e) => setSelectedRule(e.value)} options={rules}
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
            {selectedRule && showDeleteConfirmation &&
                <ConfirmDialog visible={showDeleteConfirmation} onHide={onCancelDelete} accept={onConfirmDelete}
                               reject={onCancelDelete}
                               header={__('Confirm Delete', 'sim-league-toolkit')}
                               icon='pi pi-exclamation-triangle'
                               acceptLabel={__('Yes', 'sim-league-toolkit)')}
                               rejectLabel={__('No', 'sim-league-toolkit')}
                               message={__('Are you sure you want to delete the rule: ', 'sim-league-toolkit') + ' "' + selectedRule.rule + '"? '}
                               style={{maxWidth: '50%'}}/>
            }
            <BusySpinner isActive={isBusy}/>
        </>
    )
}