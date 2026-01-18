import {__} from '@wordpress/i18n';
import {useEffect, useState} from '@wordpress/element';
import apiFetch from '@wordpress/api-fetch';

import {Dropdown, DropdownChangeEvent} from 'primereact/dropdown';
import {Game} from './Game';
import {gamesGetRoute} from './gameApiRoutes';
import {HttpMethod} from '../shared/HttpMethod';
import {ListItem} from '../shared/ListItem';
import {ValidationError} from '../shared/ValidationError';

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
                     path: gamesGetRoute(),
                     method: HttpMethod.GET
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
    };

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
    );
};