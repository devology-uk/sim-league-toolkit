import {__} from '@wordpress/i18n';
import {useState} from '@wordpress/element';

import {Dropdown, DropdownChangeEvent} from 'primereact/dropdown';

import {ListItem} from '../../types/ListItem';
import {ValidationError} from '../../components/ValidationError';
import {useEventClassesByGame} from '../../../features/eventClass';

interface EventClassSelectorProps {
    disabled?: boolean;
    eventClassId?: number;
    gameId: number;
    isInvalid?: boolean;
    onSelectedItemChanged: (itemId: number) => void;
    validationMessage?: string;
}

export const EventClassSelector = ({
                                       onSelectedItemChanged,
                                       gameId,
                                       eventClassId = 0,
                                       disabled = false,
                                       isInvalid = false,
                                       validationMessage = ''
                                   }: EventClassSelectorProps) => {
    const {data = [], isLoading} = useEventClassesByGame(gameId);
    const [selectedItemId, setSelectedItemId] = useState(eventClassId);

    const onSelect = (e: DropdownChangeEvent) => {
        setSelectedItemId(e.target.value);
        onSelectedItemChanged(e.target.value);
    };

    const listItems: ListItem[] = ([{value: 0, label: __('Please select...', 'sim-league-toolkit')}] as ListItem[])
        .concat(data.map(i => ({
            value: i.id,
            label: i.name
        })));

    return (
        <>
            <label htmlFor='event-class-selector'>{__('Event Class', 'sim-league-toolkit')}</label>
            <Dropdown id='event-class-selector' value={selectedItemId} options={listItems} onChange={onSelect}
                      optionLabel='label'
                      optionValue='value' disabled={disabled || isLoading}
                      style={{marginLeft: '.5rem', marginRight: '.5rem'}}/>
            <ValidationError
                message={validationMessage}
                show={isInvalid}/>
        </>
    );
};
