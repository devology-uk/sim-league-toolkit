import {__} from '@wordpress/i18n';
import {useEffect, useState} from '@wordpress/element';

import {Dropdown, DropdownChangeEvent} from 'primereact/dropdown';

import {ListItem} from '../../types/ListItem';
import {useScoringSets} from '../../hooks/useScoringSets';
import {ValidationError} from '../shared/ValidationError';

interface ScoringSetSelectorProps {
    onSelectedItemChanged: (item: number) => void;
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
    const {scoringSets} = useScoringSets();

    const [selectedItemId, setSelectedItemId] = useState(scoringSetId);

    useEffect(() => {
        setSelectedItemId(scoringSetId);
    }, [scoringSetId]);

    const onSelect = (e: DropdownChangeEvent) => {
        setSelectedItemId(e.target.value);
        onSelectedItemChanged(e.target.value);
    };

    const listItems: ListItem[] = ([{
        value: 0,
        label: __('Please select...', 'sim-league-toolkit')
    }] as ListItem[]).concat(scoringSets.map(i => ({
        value: i.id,
        label: i.name
    })));

    return (
        <div className='flex flex-column align-items-stretch gap-2'>
            <label htmlFor='scoring-set-selector'>{__('Scoring Set', 'sim-league-toolkit')}</label>
            <Dropdown id='scoring-set-selector' value={selectedItemId} options={listItems} onChange={onSelect}
                      optionLabel='label'
                      optionValue='value' disabled={disabled}/>
            <ValidationError
                message={validationMessage}
                show={isInvalid}/>
        </div>
    );
};