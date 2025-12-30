import {__} from '@wordpress/i18n';
import {useEffect, useState} from '@wordpress/element';
import apiFetch from '@wordpress/api-fetch';

import {Dropdown} from 'primereact/dropdown';
import {ValidationError} from '../shared/ValidationError';


export const GameSelector = ({onSelectedItemChanged, disabled = false, isInvalid = false, validationMessage = ''}) => {
    const [games, setGames] = useState([]);
    const [selectedItem, setSelectedItem] = useState(0);

    useEffect(() => {
        apiFetch({path: '/sltk/v1/game'}).then((r) => {
            setGames(r);
        });
    }, []);

    const onSelect = (evt) => {
        setSelectedItem(evt.target.value);
        onSelectedItemChanged(evt.target.value);
    }

    const items = [{value: 0, label: __('Please select...', 'sim-league-toolkit')}].concat(games.map(g => ({
        value: g.id,
        label: g.name
    })));

    return (
        <>
            <label htmlFor='game-selector'>{__('Game', 'sim-league-toolkit')}</label>
            <Dropdown id='game-selector' value={selectedItem} options={items} onChange={onSelect} optionLabel='label'
                      optionValue='value' disabled={disabled}/>
            <ValidationError
                message={validationMessage}
                show={isInvalid}/>
        </>
    )
}