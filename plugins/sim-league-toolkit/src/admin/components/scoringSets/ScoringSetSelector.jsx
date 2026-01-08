import {__} from '@wordpress/i18n';
import {useEffect, useState} from '@wordpress/element';
import apiFetch from '@wordpress/api-fetch';

import {Dropdown} from 'primereact/dropdown';
import {ValidationError} from '../shared/ValidationError';

export const ScoringSetSelector = ({onSelectedItemChanged, scoringSetId = 0, disabled = false, isInvalid = false, validationMessage = ''}) => {
    const [items, setItems] = useState([]);
    const [selectedItem, setSelectedItem] = useState(scoringSetId);

    useEffect(() => {
        apiFetch({
            path: '/sltk/v1/scoring-set',
            method: 'GET',
        }).then((r) => {
            setItems(r);
        });
    }, []);

    const onSelect = (evt) => {
        setSelectedItem(evt.target.value);
        onSelectedItemChanged(evt.target.value);
    }

    const itemOptions = [{value: 0, label: __('Please select...', 'sim-league-toolkit')}].concat(items.map(i => ({
        value: i.id,
        label: i.name
    })));

    return (
        <div className='flex flex-column align-items-stretch gap-2'>
            <label htmlFor='scoring-set-selector'>{__('Scoring Set', 'sim-league-toolkit')}</label>
            <Dropdown id='scoring-set-selector' value={selectedItem} options={itemOptions} onChange={onSelect} optionLabel='label'
                      optionValue='value' disabled={disabled}/>
            <ValidationError
                message={validationMessage}
                show={isInvalid}/>
        </div>
    )
}