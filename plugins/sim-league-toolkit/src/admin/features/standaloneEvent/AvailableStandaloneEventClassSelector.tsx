import {useState, useEffect} from '@wordpress/element';
import {DropdownChangeEvent, Dropdown} from 'primereact/dropdown';
import {ListItem} from '../../types/ListItem';
import {__} from '@wordpress/i18n';
import {ValidationError} from '../../components/ValidationError';
import {useAvailableStandaloneEventClasses} from '../../../features/standaloneEvent';

interface AvailableStandaloneEventClassSelectorProps {
    standaloneEventId: number;
    disabled?: boolean;
    isInvalid?: boolean;
    reload: boolean;
    onSelectedItemChanged: (itemId: number) => void;
    validationMessage?: string;
}

export const AvailableStandaloneEventClassSelector = ({
                                                          standaloneEventId,
                                                          disabled = false,
                                                          isInvalid = false,
                                                          reload = false,
                                                          onSelectedItemChanged,
                                                          validationMessage = ''
                                                      }: AvailableStandaloneEventClassSelectorProps) => {

    const {data: items = [], isLoading, refetch} = useAvailableStandaloneEventClasses(standaloneEventId);
    const [selectedItemId, setSelectedItemId] = useState(0);

    useEffect(() => {
        if (!reload) {
            return;
        }
        refetch().then(() => setSelectedItemId(0));
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
            <label htmlFor='standalone-event-class-selector'>{__('Event Class', 'sim-league-toolkit')}</label>
            <Dropdown id='standalone-event-class-selector' value={selectedItemId} options={listItems} onChange={onSelect}
                      optionLabel='label'
                      optionValue='value' disabled={disabled || isLoading}
                      style={{marginLeft: '.5rem', marginRight: '.5rem'}}/>
            <ValidationError
                message={validationMessage}
                show={isInvalid}/>
        </>
    );
};
