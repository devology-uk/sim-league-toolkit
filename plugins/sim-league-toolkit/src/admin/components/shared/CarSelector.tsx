import {__} from '@wordpress/i18n';
import {useEffect, useState} from '@wordpress/element';
import apiFetch from '@wordpress/api-fetch';

import {Dropdown} from 'primereact/dropdown';

import {ValidationError} from './ValidationError';


export const CarSelector = ({
                                gameId,
                                onSelectedItemChanged,
                                carId = 0,
                                carClass = '*',
                                disabled = false,
                                isInvalid = false,
                                validationMessage = ''
                            }) => {
    const [items, setItems] = useState([]);
    const [selectedItem, setSelectedItem] = useState(carId);

    useEffect(() => {
        apiFetch({
            path: `/sltk/v1/game/${gameId}/cars/${carClass}`,
            method: 'GET',
        }).then((r) => {
            setItems(r);
        });
    }, [gameId, carClass]);

    const onSelect = (evt) => {
        setSelectedItem(evt.target.value);
        onSelectedItemChanged(evt.target.value);
    }

    const itemOptions = [{value: 0, label: __('Please select...', 'sim-league-toolkit')}].concat(items.map(i => ({
        value: i.id,
        label: `${i.name} (${i.year})`
    })));

    return (
        <>
            <label htmlFor='car-selector'>{__('Car', 'sim-league-toolkit')}</label>
            <Dropdown id='car-selector' value={selectedItem} options={itemOptions} onChange={onSelect}
                      optionLabel='label'
                      optionValue='value' disabled={disabled}/>
            <ValidationError
                message={validationMessage}
                show={isInvalid}/>
        </>
    )
}