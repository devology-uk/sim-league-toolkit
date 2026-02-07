import {__} from '@wordpress/i18n';
import {FormEvent} from 'react';
import {useEffect, useState} from '@wordpress/element';

import {Dialog} from 'primereact/dialog';
import {InputText} from 'primereact/inputtext';
import {InputTextarea} from 'primereact/inputtextarea';

import {BusyIndicator} from '../shared/BusyIndicator';
import {CancelButton} from '../shared/CancelButton';
import {RuleList} from './RuleList';
import {SaveSubmitButton} from '../shared/SaveSubmitButton';
import {ValidationError} from '../shared/ValidationError';
import {useRuleSets} from '../../hooks/useRuleSets';
import {RuleSetFormData} from '../../types/RuleSetFormData';
import {RuleSet} from '../../types/RuleSet';

interface RuleSetEditorProps {
    show: boolean;
    onSaved: () => void;
    onCancelled: () => void;
    ruleSet: RuleSet | null;
}

export const RuleSetEditor = ({show, onSaved, onCancelled, ruleSet = null}: RuleSetEditorProps) => {

    const {createRuleSet, isLoading, updateRuleSet} = useRuleSets();

    const [name, setName] = useState('');
    const [description, setDescription] = useState('');
    const [validationErrors, setValidationErrors] = useState([]);

    useEffect(() => {
        if (ruleSet === null) {
            return;
        }

        setDescription(ruleSet.description);
        setName(ruleSet.name);
    }, [ruleSet]);

    const resetForm = () => {
        setName('');
        setDescription('');
    };

    const onSave = async (e: FormEvent<HTMLFormElement>) => {
        e.preventDefault();

        if (!validate()) {
            return;
        }

        const formData: RuleSetFormData = {
            name: name,
            description: description
        };

        if (!ruleSet) {
            await createRuleSet(formData);
        } else {
            await updateRuleSet(ruleSet.id, formData);
        }

        onSaved();
        resetForm();
    };

    const validate = () => {
        const errors = [];

        if (!name || name.length < 5) {
            errors.push('name');
        }

        if (!description || description.length < 15) {
            errors.push('description');
        }

        setValidationErrors(errors);
        return errors.length === 0;
    };

    return (
        <>
            {show && (
                <Dialog visible={show} onHide={onCancelled} header={__('Rule Set', 'sim-league-toolkit')}>
                    <BusyIndicator isBusy={isLoading}/>
                    <form onSubmit={onSave} noValidate>
                        <div className='flex flex-row  align-items-stretch gap-4' style={{minWidth: '750px'}}>
                            <div className='flex flex-column align-items-stretch gap-2'>
                                <label htmlFor='rule-set-name'>{__('Name', 'sim-league-toolkit')}</label>
                                <InputText id='rule-set-name' value={name} onChange={(e) => setName(e.target.value)}
                                           placeholder={__('Enter Name', 'sim-league-toolkit')}/>
                                <ValidationError
                                    message={__('A name with at least 5 characters is required', 'sim-league-toolkit')}
                                    show={validationErrors.includes('name')}/>

                                <label htmlFor='rule-set-description'>{__('Description', 'sim-league-toolkit')}</label>
                                <InputTextarea id='rule-set-description' value={description}
                                               onChange={(e) => setDescription(e.target.value)}
                                               placeholder={__('Enter Brief Description', 'sim-league-toolkit')}
                                               rows={5} cols={40}/>
                                <ValidationError
                                    message={__(
                                        'A brief description of the rule set with at least 15 characters is required.',
                                        'sim-league-toolkit')}
                                    show={validationErrors.includes('description')}/>

                            </div>
                        </div>
                        <SaveSubmitButton disabled={isLoading} name='submitRuleSet'/>
                        <CancelButton onCancel={onCancelled} disabled={isLoading}/>
                    </form>
                    {ruleSet && (<RuleList ruleSetId={ruleSet.id}/>)}
                </Dialog>
            )}
        </>
    );
};