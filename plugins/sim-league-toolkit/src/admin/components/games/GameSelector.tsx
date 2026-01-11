import apiFetch from '@wordpress/api-fetch';
import {useEffect, useState} from '@wordpress/element';
import {__} from '@wordpress/i18n';

import {Dropdown, DropdownChangeEvent} from 'primereact/dropdown';
import {ListItem} from "../shared/ListItem";
import {ValidationError} from '../shared/ValidationError';
import {Game} from "./Game";

interface GameSelectorProps {
    onSelectedItemChanged: (item: Game) => void;
    gameId: number;
    disabled?: boolean;
    isInvalid?: boolean;
    validationMessage?: string;
}

export const GameSelector = ({
                                 onSelectedItemChanged,
                                 gameId = 0,
                                 disabled = false,
                                 isInvalid = false,
                                 validationMessage = ''
                             }: GameSelectorProps) => {
    const [items, setItems] = useState([]);
    const [selectedItemId, setSelectedItemId] = useState(gameId);

    useEffect(() => {
        apiFetch({
            path: '/sltk/v1/game',
            method: 'GET'
        }).then((r: Game[]) => {
            setItems(r);
        });
    }, []);

    useEffect(() => {
        setSelectedItemId(gameId);
    }, [gameId]);

    const onSelect = (e: DropdownChangeEvent) => {
        setSelectedItemId(e.target.value);
        onSelectedItemChanged(e.target.value);
    }

    const listItems: ListItem[] = [{
        value: 0,
        label: __('Please select...', 'sim-league-toolkit')
    }].concat(items.map(i => ({
        value: i.id,
        label: i.name
    })));

    return (
        <div className='flex flex-column align-items-stretch gap-2' style={{maxWidth: '350px'}}>
            <label htmlFor='game-selector'>{__('Game', 'sim-league-toolkit')}</label>
            <Dropdown id='game-selector' value={selectedItemId} options={listItems} onChange={onSelect}
                      optionLabel='label'
                      optionValue='value' disabled={disabled}/>
            <ValidationError
                message={validationMessage}
                show={isInvalid}/>
        </div>
    )
}