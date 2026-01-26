import {__} from '@wordpress/i18n';
import {useEffect, useState} from '@wordpress/element';

import {Dropdown, DropdownChangeEvent} from 'primereact/dropdown';

import {ListItem} from '../../types/ListItem';
import {useRuleSets} from '../../hooks/useRuleSets';

interface RuleSetSelectorProps {
    onSelectedItemChanged: (ruleSetId: number) => void;
    ruleSetId?: number;
    disabled?: boolean;
}

export const RuleSetSelector = ({onSelectedItemChanged, ruleSetId = 0, disabled = false}: RuleSetSelectorProps) => {
    const {isLoading, ruleSets} = useRuleSets();
    const [selectedItemId, setSelectedItemId] = useState(ruleSetId);

    useEffect(() => {
        if (!ruleSetId) {
            setSelectedItemId(0);
            return;
        }

        setSelectedItemId(ruleSetId);
    }, [ruleSetId]);

    const onSelect = (e: DropdownChangeEvent) => {
        setSelectedItemId(e.target.value);
        onSelectedItemChanged(e.target.value);
    };

    const listItems: ListItem[] = ([{
        value: 0,
        label: __('None', 'sim-league-toolkit')
    }] as ListItem[]).concat(ruleSets.map(i => ({
        value: i.id,
        label: i.name
    })));

    return (
        <>
            <label htmlFor='rule-set-selector'>{__('Rule Set', 'sim-league-toolkit')}</label>
            <Dropdown id='rule-set-selector' value={selectedItemId} options={listItems} onChange={onSelect}
                      optionLabel='label'
                      optionValue='value' disabled={disabled || isLoading}/>
        </>
    );
};