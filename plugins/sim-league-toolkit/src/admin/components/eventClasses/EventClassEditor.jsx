import {__} from '@wordpress/i18n';
import {useEffect, useState} from '@wordpress/element';
import apiFetch from '@wordpress/api-fetch';

import {Dialog} from 'primereact/dialog';
import {InputText} from 'primereact/inputtext';
import {InputTextarea} from 'primereact/inputtextarea';


import {BusySpinner} from '../BusySpinner';
import {CancelButton} from '../CancelButton';
import {SaveSubmitButton} from '../SaveSubmitButton';
import {ValidationError} from '../ValidationError';

export const EventClassEditor = ({show, onSaved, onCancelled, eventClassId = 0}) => {
    const [isBusy, setIsBusy] = useState(false);
    const [gameId, setGameId] = useState(0);
    const [name, setName] = useState('');
    const [validationErrors, setValidationErrors] = useState([]);

    useEffect(() => {
        if(eventClassId === 0){
            return;
        }

        apiFetch({
            path: `/sltk/v1/event-class/${eventClassId}`,
            method: 'GET',
        }).then((r) => {
            setName(r.name);
            setIsBusy(false);
        });
    }, [])

    const resetForm = () => {
        setName('');
    }
    
    return (
        <></>
    )
}