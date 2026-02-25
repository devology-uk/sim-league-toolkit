import {__} from '@wordpress/i18n';
import {useEffect, useState} from '@wordpress/element';

import {Dropdown, DropdownChangeEvent} from 'primereact/dropdown';
import {ListItem} from '../../types/ListItem';
import {useGames} from '../../../features/game';
import {ValidationError} from '../../components/ValidationError';

interface GameSelectorProps {
    onSelectedItemChanged: (id: number) => void;
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
    const {data = [], isLoading} = useGames();

    const [selectedItemId, setSelectedItemId] = useState(gameId);

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
    } as ListItem].concat(data.map(i => ({
        value: i.id,
        label: i.name
    })));

    return (
        <div className='flex flex-column align-items-stretch gap-2' style={{maxWidth: '350px'}}>
            <label htmlFor='game-selector'>{__('Game', 'sim-league-toolkit')}</label>
            <Dropdown id='game-selector' value={selectedItemId} options={listItems} onChange={onSelect}
                      optionLabel='label'
                      optionValue='value' disabled={disabled || isLoading}/>
            <ValidationError
                message={validationMessage}
                show={isInvalid}/>
        </div>
    );
};