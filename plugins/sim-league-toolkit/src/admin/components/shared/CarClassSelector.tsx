import {__} from '@wordpress/i18n';
import {useEffect, useState} from '@wordpress/element';
import apiFetch from '@wordpress/api-fetch';

import {Dropdown, DropdownChangeEvent} from 'primereact/dropdown';

import {ListItem} from "./ListItem";
import {ValidationError} from './ValidationError';

export const CAR_CLASS_SELECTOR_DEFAULT_VALUE: string = 'any';

interface CarClassSelectorProps {
    carClass: string;
    gameId: number;
    onSelectedItemChanged: (item: string) => void;
    disabled?: boolean;
    isInvalid?: boolean;
    validationMessage?: string;
}

export const CarClassSelector = ({
                                     carClass = CAR_CLASS_SELECTOR_DEFAULT_VALUE,
                                     gameId,
                                     onSelectedItemChanged,
                                     disabled = false,
                                     isInvalid = false,
                                     validationMessage = ''
                                 }: CarClassSelectorProps) => {

    const [items, setItems] = useState([]);
    const [selectedItem, setSelectedItem] = useState(carClass);

    useEffect(() => {
        apiFetch({
            path: `/sltk/v1/game/${gameId}/car-classes`,
            method: 'GET',
        }).then((r: string[]) => {
            setItems(r);
        });
    }, [gameId]);

    useEffect(() => {
        setSelectedItem(carClass);
    }, [carClass])

    const onSelect = (e: DropdownChangeEvent) => {
        setSelectedItem(e.target.value);
        onSelectedItemChanged(e.target.value);
    }

    const listItems: ListItem[] = [{
        value: CAR_CLASS_SELECTOR_DEFAULT_VALUE,
        label: __('Please select...', 'sim-league-toolkit')
    }].concat(items.map(i => ({
        value: i,
        label: i
    })));
    return (
        <>
            <label htmlFor='car-class-selector'>{__('Car Class', 'sim-league-toolkit')}</label>
            <Dropdown id='car-class-selector' value={selectedItem} options={listItems} onChange={onSelect}
                      disabled={disabled}/>
            <ValidationError
                message={validationMessage}
                show={isInvalid}/>
        </>
    )
}