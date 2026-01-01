import {__} from '@wordpress/i18n';
import {useEffect, useState} from '@wordpress/element';
import apiFetch from '@wordpress/api-fetch';

import {Dropdown} from 'primereact/dropdown';
import {ValidationError} from '../shared/ValidationError';


export const GameSelector = ({onSelectedItemChanged, gameId = 0, disabled = false, isInvalid = false, validationMessage = ''}) => {
    const [items, setItems] = useState([]);
    const [selectedItem, setSelectedItem] = useState(gameId);

    useEffect(() => {
        apiFetch({
            path: '/sltk/v1/game',
            method: 'GET'
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
            <label htmlFor='game-selector'>{__('Game', 'sim-league-toolkit')}</label>
            <Dropdown id='game-selector' value={selectedItem} options={itemOptions} onChange={onSelect}
                      optionLabel='label'
                      optionValue='value' disabled={disabled}/>
            <ValidationError
                message={validationMessage}
                show={isInvalid}/>
        </>
    )
}