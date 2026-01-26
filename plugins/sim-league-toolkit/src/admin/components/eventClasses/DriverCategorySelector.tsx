import {__} from '@wordpress/i18n';
import {useEffect, useState} from '@wordpress/element';

import {Dropdown, DropdownChangeEvent} from 'primereact/dropdown';
import {ListItem} from '../../types/ListItem';
import {useDriverCategories} from '../../hooks/useDriverCategories';
import {ValidationError} from '../shared/ValidationError';

interface DriverCategorySelectorProps {
    disabled?: boolean;
    driverCategoryId?: number;
    isInvalid?: boolean;
    onSelectedItemChanged: (itemId: number) => void;
    validationMessage?: string;
}

export const DriverCategorySelector = ({
                                           onSelectedItemChanged,
                                           driverCategoryId = 0,
                                           disabled = false,
                                           isInvalid = false,
                                           validationMessage = ''
                                       }: DriverCategorySelectorProps) => {
    const {driverCategories, isLoading} = useDriverCategories();
    const [selectedItemId, setSelectedItemId] = useState(driverCategoryId);

    useEffect(() => {
        setSelectedItemId(driverCategoryId);
    }, [driverCategoryId]);

    const onSelect = (e: DropdownChangeEvent) => {
        setSelectedItemId(e.target.value);
        onSelectedItemChanged(e.target.value);
    };

    const listItems: ListItem[] = ([{value: 0, label: __('Please select...', 'sim-league-toolkit')} as ListItem])
        .concat(driverCategories.map(i => ({
            value: i.id,
            label: i.name
        })));

    return (
        <>
            <label htmlFor='driver-category-selector'>{__('Driver Category', 'sim-league-toolkit')}</label>
            <Dropdown id='driver-category-selector' value={selectedItemId} options={listItems} onChange={onSelect}
                      optionLabel='label'
                      optionValue='value' disabled={disabled || isLoading}/>
            <ValidationError
                message={validationMessage}
                show={isInvalid}/>
        </>
    );
};