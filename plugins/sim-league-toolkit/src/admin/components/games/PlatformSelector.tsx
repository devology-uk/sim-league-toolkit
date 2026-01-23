import {__} from '@wordpress/i18n';
import apiFetch from '@wordpress/api-fetch';
import {useEffect, useState} from '@wordpress/element';

import {Dropdown, DropdownChangeEvent} from 'primereact/dropdown';

import {HttpMethod} from '../shared/HttpMethod';
import {ListItem} from '../../types/ListItem';
import {Platform} from '../../types/Platform';
import {platformsGetRoute} from './gameApiRoutes';
import {ValidationError} from '../shared/ValidationError';

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
                     path: platformsGetRoute(gameId),
                     method: HttpMethod.GET,
                 }).then((r: Platform[]) => {
            setItems(r);
        });
    }, [gameId]);

    useEffect(() => {
        setSelectedItemId(platformId);
    }, [platformId]);

    const onSelect = (e: DropdownChangeEvent) => {
        setSelectedItemId(e.target.value);
        onSelectedItemChanged(e.target.value);
    };

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
    );
};