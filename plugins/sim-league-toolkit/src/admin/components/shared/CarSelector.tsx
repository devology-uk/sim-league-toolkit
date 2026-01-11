import {__} from '@wordpress/i18n';
import {useEffect, useState} from '@wordpress/element';
import apiFetch from '@wordpress/api-fetch';

import {Dropdown, DropdownChangeEvent} from 'primereact/dropdown';

import {Car} from "./Car";
import {ListItem} from "./ListItem";
import {ValidationError} from './ValidationError';

interface CarSelectorProps {
    gameId: number;
    onSelectedItemChanged: (item: Car) => void;
    carId?: number;
    carClass?: string;
    disabled?: boolean;
    isInvalid?: boolean;
    validationMessage?: string;
}

export const CarSelector = ({
                                gameId,
                                onSelectedItemChanged,
                                carId = 0,
                                carClass = '*',
                                disabled = false,
                                isInvalid = false,
                                validationMessage = ''
                            }: CarSelectorProps) => {
    const [items, setItems] = useState<Car[]>([]);
    const [selectedItemId, setSelectedItemId] = useState(carId);

    useEffect(() => {
        apiFetch({
            path: `/sltk/v1/game/${gameId}/cars/${carClass}`,
            method: 'GET',
        }).then((r: Car[]) => {
            setItems(r);
        });
    }, [gameId, carClass]);

    useEffect(() => {
        setSelectedItemId(carId);
    }, [carId])

    const onSelect = (e: DropdownChangeEvent) => {
        setSelectedItemId(e.target.value);
        onSelectedItemChanged(e.target.value);
    }

    const listItems: ListItem[] = ([{
        value: 0,
        label: __('Please select...', 'sim-league-toolkit')
    }] as ListItem[]).concat(items.map(i => ({
        value: i.id,
        label: `${i.name} (${i.year})`
    })));

    return (
        <>
            <label htmlFor='car-selector'>{__('Car', 'sim-league-toolkit')}</label>
            <Dropdown id='car-selector' value={selectedItemId} options={listItems} onChange={onSelect}
                      optionLabel='label'
                      optionValue='value' disabled={disabled}/>
            <ValidationError
                message={validationMessage}
                show={isInvalid}/>
        </>
    )
}