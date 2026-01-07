import {__} from '@wordpress/i18n';
import {useEffect, useState} from '@wordpress/element';
import apiFetch from '@wordpress/api-fetch';

import {Dropdown} from 'primereact/dropdown';

import {ValidationError} from './ValidationError';


export const PlatformSelector = ({
                                gameId,
                                onSelectedItemChanged,
                                platformId = 0,
                                disabled = false,
                                isInvalid = false,
                                validationMessage = ''
                            }) => {
    const [items, setItems] = useState([]);
    const [selectedItem, setSelectedItem] = useState(platformId);

    useEffect(() => {
        apiFetch({
            path: `/sltk/v1/game/${gameId}/platforms`,
            method: 'GET',
        }).then((r) => {
            setItems(r);
        });
    }, [gameId]);

    const onSelect = (evt) => {
        setSelectedItem(evt.target.value);
        onSelectedItemChanged(evt.target.value);
    }

    const itemOptions = [{value: 0, label: __('Please select...', 'sim-league-toolkit')}].concat(items.map(i => ({
        value: i.id,
        label: `${i.name}`
    })));

    return (
        <>
            <label htmlFor='platform-selector'>{__('Platform', 'sim-league-toolkit')}</label>
            <Dropdown id='platform-selector' value={selectedItem} options={itemOptions} onChange={onSelect}
                      optionLabel='label'
                      optionValue='value' disabled={disabled}/>
            <ValidationError
                message={validationMessage}
                show={isInvalid}/>
        </>
    )
}