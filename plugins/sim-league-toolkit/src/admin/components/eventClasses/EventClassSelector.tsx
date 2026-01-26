import {__} from '@wordpress/i18n';
import {useEffect, useState} from '@wordpress/element';

import {Dropdown, DropdownChangeEvent} from 'primereact/dropdown';

import {EventClass} from '../../types/EventClass';
import {ListItem} from '../../types/ListItem';
import {useEventClasses} from '../../hooks/useEventClasses';
import {ValidationError} from '../shared/ValidationError';

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
    const {findEventClassesByGameId, isLoading} = useEventClasses();

    const [items, setItems] = useState<EventClass[]>([]);
    const [selectedItemId, setSelectedItemId] = useState(eventClassId);

    useEffect(() => {
        setItems(findEventClassesByGameId(gameId));
    }, [gameId]);

    useEffect(() => {
        setSelectedItemId(eventClassId);
    }, [eventClassId]);

    const onSelect = (e: DropdownChangeEvent) => {
        setSelectedItemId(e.target.value);
        onSelectedItemChanged(e.target.value);
    };

    const listItems: ListItem[] = ([{value: 0, label: __('Please select...', 'sim-league-toolkit')}] as ListItem[])
        .concat(items.map(i => ({
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