import {useState, useEffect} from '@wordpress/element';
import {DropdownChangeEvent, Dropdown} from 'primereact/dropdown';
import {ListItem} from '../../types/ListItem';
import {__} from '@wordpress/i18n';
import {ValidationError} from '../../components/ValidationError';
<<<<<<<< HEAD:plugins/sim-league-toolkit/src/admin/features/championship/AvailableEventClassSelector.tsx
import {useAvailableChampionshipClasses} from '../../../features/championship';
========
>>>>>>>> 296a59c8d227e3a0bd5351d4345008cdb62b384f:plugins/sim-league-toolkit/src/admin/features/championships/AvailableEventClassSelector.tsx

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

    const {data: items = [], isLoading, refetch} = useAvailableChampionshipClasses(championshipId);
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
            value: i.eventClassId,
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