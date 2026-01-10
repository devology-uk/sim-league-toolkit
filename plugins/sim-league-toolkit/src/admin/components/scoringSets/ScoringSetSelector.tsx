import {__} from '@wordpress/i18n';
import {useEffect, useState} from '@wordpress/element';
import apiFetch from '@wordpress/api-fetch';

import {Dropdown, DropdownChangeEvent} from 'primereact/dropdown';

import {ListItem} from "../shared/ListItem";
import {ScoringSet} from "./ScoringSet";
import {ValidationError} from '../shared/ValidationError';

interface ScoringSetSelectorProps {
    onSelectedItemChanged: (item: ScoringSet) => void;
    scoringSetId?: number;
    disabled?: boolean;
    isInvalid?: boolean;
    validationMessage?: string;
}

export const ScoringSetSelector = ({
                                       onSelectedItemChanged,
                                       scoringSetId = 0,
                                       disabled = false,
                                       isInvalid = false,
                                       validationMessage = ''
                                   }: ScoringSetSelectorProps) => {
    const [items, setItems] = useState<ScoringSet[]>([]);
    const [selectedItem, setSelectedItem] = useState(scoringSetId);

    useEffect(() => {
        apiFetch({
            path: '/sltk/v1/scoring-set',
            method: 'GET',
        }).then((r: ScoringSet[]) => {
            setItems(r);
        });
    }, []);

    const onSelect = (e: DropdownChangeEvent) => {
        setSelectedItem(e.target.value);
        onSelectedItemChanged(e.target.value);
    }

    const listItems: ListItem[] = ([{
        value: 0,
        label: __('Please select...', 'sim-league-toolkit')
    }] as ListItem[]).concat(items.map(i => ({
        value: i.id,
        label: i.name
    })));

    return (
        <div className='flex flex-column align-items-stretch gap-2'>
            <label htmlFor='scoring-set-selector'>{__('Scoring Set', 'sim-league-toolkit')}</label>
            <Dropdown id='scoring-set-selector' value={selectedItem} options={listItems} onChange={onSelect}
                      optionLabel='label'
                      optionValue='value' disabled={disabled}/>
            <ValidationError
                message={validationMessage}
                show={isInvalid}/>
        </div>
    )
}