import {__} from '@wordpress/i18n';
import {useEffect, useState} from '@wordpress/element';

import {Dropdown, DropdownChangeEvent} from 'primereact/dropdown';
import {ListItem} from '../../types/ListItem';
import {useCarClasses} from '../../hooks/useCarClasses';
import {ValidationError} from '../shared/ValidationError';

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

    const {carClasses, isLoading} = useCarClasses(gameId);

    const [selectedItem, setSelectedItem] = useState(carClass);

    useEffect(() => {
        setSelectedItem(carClass);
    }, [carClass]);

    const onSelect = (e: DropdownChangeEvent) => {
        setSelectedItem(e.target.value);
        onSelectedItemChanged(e.target.value);
    };

    const listItems: ListItem[] = [{
        value: CAR_CLASS_SELECTOR_DEFAULT_VALUE,
        label: __('Please select...', 'sim-league-toolkit')
    } as ListItem].concat(carClasses.map(i => ({
        value: i,
        label: i
    })));
    return (
        <>
            <label htmlFor='car-class-selector'>{__('Car Class', 'sim-league-toolkit')}</label>
            <Dropdown id='car-class-selector' value={selectedItem} options={listItems} onChange={onSelect}
                      disabled={disabled || isLoading}/>
            <ValidationError
                message={validationMessage}
                show={isInvalid}/>
        </>
    );
};