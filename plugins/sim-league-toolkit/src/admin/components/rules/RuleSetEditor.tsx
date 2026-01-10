import {__} from '@wordpress/i18n';
import {useEffect, useState} from '@wordpress/element';
import apiFetch from '@wordpress/api-fetch';

import {Dialog} from 'primereact/dialog';
import {InputText} from 'primereact/inputtext';
import {InputTextarea} from 'primereact/inputtextarea';


import {BusySpinner} from '../shared/BusySpinner';
import {CancelButton} from '../shared/CancelButton';
import {RuleList} from './RuleList';
import {SaveSubmitButton} from '../shared/SaveSubmitButton';
import {ValidationError} from '../shared/ValidationError';
import {RuleSet} from "./RuleSet";
import {FormEvent} from "react";

interface RuleSetEditorProps {
    show: boolean;
    onSaved: () => void;
    onCancelled: () => void;
    ruleSetId: number | null;
}

export const RuleSetEditor = ({show, onSaved, onCancelled, ruleSetId = 0}: RuleSetEditorProps) => {

    const [isBusy, setIsBusy] = useState(false);
    const [name, setName] = useState('');
    const [description, setDescription] = useState('');
    const [validationErrors, setValidationErrors] = useState([]);

    useEffect(() => {
        if (ruleSetId === 0) {
            return;
        }

        apiFetch({
            path: `/sltk/v1/rule-set/${ruleSetId}`,
            method: 'GET',
        }).then((r: RuleSet) => {
            setName(r.name);
            setDescription(r.description);
            setIsBusy(false);
        });
    }, [])

    const resetForm = () => {
        setName('');
        setDescription('');
    }

    const onSave = (e: FormEvent<HTMLFormElement>) => {
        e.preventDefault();

        if (!validate()) {
            return;
        }

        setIsBusy(true);
        const ruleSet: RuleSet = {
            name: name,
            description: description
        }

        if (ruleSetId && ruleSetId > 0) {
            ruleSet.id = ruleSetId;
        }


        apiFetch({
            path: '/sltk/v1/rule-set',
            method: 'POST',
            data: ruleSet,
        }).then(() => {
            onSaved();

            resetForm();
            setIsBusy(false);
        });
    }

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
    }

    return (
        <>
            {show && (
                <Dialog visible={show} onHide={onCancelled} header={__('Rule Set', 'sim-league-toolkit')}>
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
                                    message={__('A brief description of the rule set with at least 15 characters is required.', 'sim-league-toolkit')}
                                    show={validationErrors.includes('description')}/>

                            </div>
                        </div>
                        <SaveSubmitButton disabled={isBusy} name='submitRuleSet'/>
                        <CancelButton onCancel={onCancelled} disabled={isBusy}/>
                    </form>
                    {ruleSetId && (<RuleList ruleSetId={ruleSetId}/>)}
                    <BusySpinner isBusy={isBusy}/>
                </Dialog>
            )}
        </>
    )
}