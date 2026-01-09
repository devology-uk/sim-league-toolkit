import {__} from '@wordpress/i18n';
import {useEffect, useState} from '@wordpress/element';
import apiFetch from '@wordpress/api-fetch';

import {Dropdown} from 'primereact/dropdown';

import {ValidationError} from '../shared/ValidationError';


export const DriverCategorySelector = ({onSelectedItemChanged, driverCategoryId = 0, disabled = false, isInvalid = false, validationMessage = ''}) => {
    const [items, setItems] = useState([]);
    const [selectedItem, setSelectedItem] = useState(driverCategoryId);

    useEffect(() => {
        apiFetch({
            path: '/sltk/v1/driver-category',
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
        <>
            <label htmlFor='driver-category-selector'>{__('Driver Category', 'sim-league-toolkit')}</label>
            <Dropdown id='driver-category-selector' value={selectedItem} options={itemOptions} onChange={onSelect} optionLabel='label'
                      optionValue='value' disabled={disabled}/>
            <ValidationError
                message={validationMessage}
                show={isInvalid}/>
        </>
    )
}