import {__} from '@wordpress/i18n';
import {useEffect, useState} from '@wordpress/element';

import {Dropdown} from 'primereact/dropdown';
import {Dialog} from 'primereact/dialog';
import {InputText} from 'primereact/inputtext';
import {InputTextarea} from 'primereact/inputtextarea';


import {BusySpinner} from '../BusySpinner';
import {SaveSubmitButton} from '../SaveSubmitButton';
import {CancelButton} from '../CancelButton';
import {ValidationError} from '../ValidationError';
import apiFetch from '@wordpress/api-fetch';

export const RuleSetEditor = ({show, onSaved, onCancelled, ruleSetId = 0}) => {
    const types = [
        {'label': 'Any', 'value': 'any'},
        {'label': 'Championship', 'value': 'championship'},
        {'label': 'Individual Event', 'value': 'event'}
    ];

    const [isBusy, setIsBusy] = useState(false);
    const [name, setName] = useState('');
    const [description, setDescription] = useState('');
    const [selectedType, setSelectedType] = useState('any');
    const [validationErrors, setValidationErrors] = useState([]);

    useEffect(() => {
        if(ruleSetId === 0){
            return;
        }

        apiFetch({
            path: `/sltk/v1/rule-set/${ruleSetId}`,
            method: 'GET',
        }).then((r) => {
            setName(r.name);
            setDescription(r.description);
            setSelectedType(r.type);
            setIsBusy(false);
        })
    }, [])

    const resetForm = () => {
        setName('');
        setDescription('');
        setSelectedType('any');
    }

    const onSave = (evt) => {
        evt.preventDefault();

        if(!validate()) {
            return;
        }

        setIsBusy(true);
        const ruleSet = {
            name: name,
            description: description,
            type: selectedType
        }

        if(ruleSetId && ruleSetId > 0) {
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

        if (!selectedType || selectedType.length < 3) {
            errors.push('type');
        }

        setValidationErrors(errors);
        return errors.length === 0;
    }

    return (
        <>
            {show && (
                <Dialog visible={show} onHide={onCancelled} header={__('Rule Set', 'sim-league-toolkit')}>
                    <form onSubmit={onSave} noValidate>
                        <div className='flex flex-row  align-items-stretch gap-4' style={{minWidth: '750spx'}}>
                            <div className='flex flex-column align-items-stretch gap-2'>
                                <label htmlFor='rule-set-name'>{__('Name', 'sim-league-toolkit')}</label>
                                <InputText id='rule-set-name' value={name} onChange={(e) => setName(e.target.value)}
                                           placeholder={__('Enter Name', 'sim-league-toolkit')}/>
                                <ValidationError
                                    message={__('A name with at least 5 characters is required', 'sim-league-toolkit')}
                                    show={validationErrors.includes('name')}/>

                                <label htmlFor='rule-set-description'>{__('Description', 'sim-league-toolkit')}</label>
                                <InputTextarea id='rule-set-description' value={description} onChange={(e) => setDescription(e.target.value)}
                                           placeholder={__('Enter Brief Description', 'sim-league-toolkit')}
                                rows={5} cols={40}/>
                                <ValidationError
                                    message={__('A brief description of the rule set with at least 15 characters is required.', 'sim-league-toolkit')}
                                    show={validationErrors.includes('description')}/>
                                <label htmlFor='rule-set-type'>{__('Rule Set Type', 'sim-league-toolkit')}</label>
                                <Dropdown inputId='rule-set-type' value={selectedType}
                                          onChange={(e) => setSelectedType(e.value)} options={types}
                                          optionLabel='label'
                                          optionValue='value'/>
                                <ValidationError
                                    message={__('A type for the rule set must be selected', 'sim-league-toolkit')}
                                    show={validationErrors.includes('type')}/>
                            </div>
                        </div>
                        <SaveSubmitButton disable={isBusy} name='submitRuleSet'/>
                        <CancelButton onCancel={onCancelled} disabled={isBusy}/>
                        <BusySpinner isActive={isBusy}/>
                    </form>
                </Dialog>
            )}
        </>
    )
}