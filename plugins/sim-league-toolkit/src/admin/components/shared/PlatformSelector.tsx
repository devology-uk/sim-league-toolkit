import {__} from '@wordpress/i18n';
import {useEffect, useState} from '@wordpress/element';
import apiFetch from '@wordpress/api-fetch';

import {Dropdown} from 'primereact/dropdown';

import {ValidationError} from './ValidationError';
import {Platform} from "./Platform";
import {ListItem} from "./ListItem";

interface PlatformSelectorProps {
    gameId: number;
    onSelectedItemChanged: (item: Platform) => void;
    platformId?: number;
    disabled?: boolean;
    isInvalid?: boolean;
    validationMessage?: string;
}


export const PlatformSelector = ({
                                     gameId,
                                     onSelectedItemChanged,
                                     platformId = 0,
                                     disabled = false,
                                     isInvalid = false,
                                     validationMessage = ''
                                 }: PlatformSelectorProps) => {
    const [items, setItems] = useState<Platform[]>([]);
    const [selectedItemId, setSelectedItemId] = useState(platformId);

    useEffect(() => {
        apiFetch({
            path: `/sltk/v1/game/${gameId}/platforms`,
            method: 'GET',
        }).then((r: Platform[]) => {
            setItems(r);
        });
    }, [gameId]);

    const onSelect = (evt) => {
        setSelectedItemId(evt.target.value);
        onSelectedItemChanged(evt.target.value);
    }

    const listItems: ListItem[] = ([{
        value: 0,
        label: __('Please select...', 'sim-league-toolkit')
    }] as ListItem[]).concat(items.map(i => ({
        value: i.id,
        label: `${i.name}`
    })));

    return (
        <>
            <label htmlFor='platform-selector'>{__('Platform', 'sim-league-toolkit')}</label>
            <Dropdown id='platform-selector' value={selectedItemId} options={listItems} onChange={onSelect}
                      optionLabel='label'
                      optionValue='value' disabled={disabled}/>
            <ValidationError
                message={validationMessage}
                show={isInvalid}/>
        </>
    )
}