import {__} from '@wordpress/i18n';
import {useEffect, useState} from '@wordpress/element';
import apiFetch from '@wordpress/api-fetch';

import {Dropdown, DropdownChangeEvent} from 'primereact/dropdown';

import {RuleSet} from "../../types/RuleSet";
import {ListItem} from "../../types/ListItem";
import {ruleSetsGetRoute} from '../../api/endpoints/rulesApiRoutes';
import {HttpMethod} from '../../enums/HttpMethod';

interface RuleSetSelectorProps {
    onSelectedItemChanged: (ruleSetId: number) => void;
    ruleSetId?: number;
    disabled?: boolean;
}

export const RuleSetSelector = ({onSelectedItemChanged, ruleSetId = 0, disabled = false}: RuleSetSelectorProps) => {
    const [items, setItems] = useState<RuleSet[]>([]);
    const [selectedItemId, setSelectedItemId] = useState(ruleSetId);

    useEffect(() => {
        apiFetch({
            path: ruleSetsGetRoute(),
            method: HttpMethod.GET,
        }).then((r: RuleSet[]) => {
            setItems(r);
        });
    }, []);

    useEffect(() => {
        if(!ruleSetId) {
            setSelectedItemId(0);
            return;
        }

        setSelectedItemId(ruleSetId);
    }, [ruleSetId])

    const onSelect = (e: DropdownChangeEvent) => {
        setSelectedItemId(e.target.value);
        onSelectedItemChanged(e.target.value);
    }

    const listItems: ListItem[] = ([{
        value: 0,
        label: __('None', 'sim-league-toolkit')
    }] as ListItem[]).concat(items.map(i => ({
        value: i.id,
        label: i.name
    })));

    return (
        <>
            <label htmlFor='rule-set-selector'>{__('Rule Set', 'sim-league-toolkit')}</label>
            <Dropdown id='rule-set-selector' value={selectedItemId} options={listItems} onChange={onSelect}
                      optionLabel='label'
                      optionValue='value' disabled={disabled}/>
        </>
    )
}