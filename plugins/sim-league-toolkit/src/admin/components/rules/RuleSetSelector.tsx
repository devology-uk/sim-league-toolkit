import {__} from '@wordpress/i18n';
import {useEffect, useState} from '@wordpress/element';
import apiFetch from '@wordpress/api-fetch';

import {Dropdown} from 'primereact/dropdown';

export const RuleSetSelector = ({onSelectedItemChanged, ruleSetId = 0, disabled = false}) => {
    const [items, setItems] = useState([]);
    const [selectedItem, setSelectedItem] = useState(ruleSetId);

    useEffect(() => {
        apiFetch({
            path: '/sltk/v1/rule-set',
            method: 'GET',
        }).then((r) => {
            setItems(r);
        });
    }, []);

    const onSelect = (evt) => {
        setSelectedItem(evt.target.value);
        onSelectedItemChanged(evt.target.value);
    }

    const itemOptions = [{value: 0, label: __('None', 'sim-league-toolkit')}].concat(items.map(i => ({
        value: i.id,
        label: i.name
    })));

    return (
        <>
            <label htmlFor='rule-set-selector'>{__('Rule Set', 'sim-league-toolkit')}</label>
            <Dropdown id='rule-set-selector' value={selectedItem} options={itemOptions} onChange={onSelect} optionLabel='label'
                      optionValue='value' disabled={disabled}/>
        </>
    )
}