import {__} from '@wordpress/i18n';
import {useEffect, useState} from '@wordpress/element';
import apiFetch from '@wordpress/api-fetch';

import {Dropdown, DropdownChangeEvent} from 'primereact/dropdown';

import {ValidationError} from '../shared/ValidationError';
import {DriverCategory} from "./DriverCategory";
import {ListItem} from "../shared/ListItem";

interface DriverCategorySelectorProps {
    disabled?: boolean;
    driverCategoryId?: number;
    isInvalid?: boolean;
    onSelectedItemChanged: (item: DriverCategory) => void;
    validationMessage?: string;
}

export const DriverCategorySelector = ({
                                           onSelectedItemChanged,
                                           driverCategoryId = 0,
                                           disabled = false,
                                           isInvalid = false,
                                           validationMessage = ''
                                       }: DriverCategorySelectorProps) => {
    const [items, setItems] = useState<DriverCategory[]>([]);
    const [selectedItemId, setSelectedItemId] = useState(driverCategoryId);

    useEffect(() => {
        apiFetch({
            path: '/sltk/v1/driver-category',
            method: 'GET',
        }).then((r: DriverCategory[]) => {
            setItems(r);
        });
    }, []);

    const onSelect = (e: DropdownChangeEvent) => {
        setSelectedItemId(e.target.value);
        onSelectedItemChanged(e.target.value);
    }


    const listItems: ListItem[] = ([{value: 0, label: __('Please select...', 'sim-league-toolkit')}] as ListItem[])
        .concat(items.map(i => ({
        value: i.id,
        label: i.name
    })));

    return (
        <>
            <label htmlFor='driver-category-selector'>{__('Driver Category', 'sim-league-toolkit')}</label>
            <Dropdown id='driver-category-selector' value={selectedItemId} options={listItems} onChange={onSelect}
                      optionLabel='label'
                      optionValue='value' disabled={disabled}/>
            <ValidationError
                message={validationMessage}
                show={isInvalid}/>
        </>
    )
}