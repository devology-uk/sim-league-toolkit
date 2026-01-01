import {__} from '@wordpress/i18n';
import {useEffect, useState} from '@wordpress/element';
import apiFetch from '@wordpress/api-fetch';

import {Dropdown} from 'primereact/dropdown';

import {ValidationError} from './ValidationError';

export const CAR_CLASS_SELECTOR_DEFAULT_VALUE = 'any';

export const CarClassSelector = ({carClass = CAR_CLASS_SELECTOR_DEFAULT_VALUE, gameId, onSelectedItemChanged, disabled = false, isInvalid = false, validationMessage = ''}) => {

    const [items, setItems] = useState([]);
    const [selectedItem, setSelectedItem] = useState(carClass);

    useEffect(() => {
        apiFetch({
            path: `/sltk/v1/game/${gameId}/car-classes`,
            method: 'GET',
        }).then((r) => {
            setItems(r);
        });
    }, [gameId]);

    const onSelect = (evt) => {
        setSelectedItem(evt.target.value);
        onSelectedItemChanged(evt.target.value);
    }

    const itemOptions = [{value: CAR_CLASS_SELECTOR_DEFAULT_VALUE, label: __('Please select...', 'sim-league-toolkit')}].concat(items.map(i => ({
        value: i,
        label: i
    })));
    return (
        <>
            <label htmlFor='car-class-selector'>{__('Car Class', 'sim-league-toolkit')}</label>
            <Dropdown id='car-class-selector' value={selectedItem} options={itemOptions} onChange={onSelect} disabled={disabled}/>
            <ValidationError
                message={validationMessage}
                show={isInvalid}/>
        </>
    )
}