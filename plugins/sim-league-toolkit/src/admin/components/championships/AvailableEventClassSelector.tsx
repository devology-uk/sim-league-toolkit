import {useAvailableEventClasses} from '../../hooks/useAvailableEventClasses';
import {useState, useEffect} from '@wordpress/element';
import {EventClass} from '../../types/EventClass';
import {DropdownChangeEvent, Dropdown} from 'primereact/dropdown';
import {ListItem} from '../../types/ListItem';
import {__} from '@wordpress/i18n';
import {ValidationError} from '../shared/ValidationError';

interface AvailableEventClassSelectorProps {
    championshipId: number;
    disabled?: boolean;
    isInvalid?: boolean;
    reload: boolean;
    onSelectedItemChanged: (itemId: number) => void;
    validationMessage?: string;
}

export const AvailableEventClassSelector = ({
                                                championshipId,
                                                disabled = false,
                                                isInvalid = false,
                                                reload = false,
                                                onSelectedItemChanged,
                                                validationMessage = ''
                                            }: AvailableEventClassSelectorProps) => {

    const {availableEventClasses, isLoading, refresh} = useAvailableEventClasses(championshipId);
    const [items, setItems] = useState<EventClass[]>([]);
    const [selectedItemId, setSelectedItemId] = useState(0);

    useEffect(() => {
        if (isLoading) {
            return;
        }

        setItems(availableEventClasses ?? []);
    }, [isLoading]);

    useEffect(() => {
        if (!reload) {
            return;
        }
        refresh().then(_ => setSelectedItemId(0));
    }, [reload]);

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