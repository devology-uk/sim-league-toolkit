import {__} from '@wordpress/i18n';
import {useEffect, useState} from '@wordpress/element';

import {Dropdown, DropdownChangeEvent} from 'primereact/dropdown';
import {ListItem} from '../../types/ListItem';
import {Platform} from '../../types/Platform';
import {usePlatforms} from '../../hooks/usePlatforms';
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

    const {isLoading, platforms} = usePlatforms(gameId);

    const [selectedItemId, setSelectedItemId] = useState(platformId);

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
    }] as ListItem[]).concat(platforms.map(i => ({
        value: i.id,
        label: `${i.name}`
    })));

    return (
        <>
            <label htmlFor='platform-selector'>{__('Platform', 'sim-league-toolkit')}</label>
            <Dropdown id='platform-selector' value={selectedItemId} options={listItems} onChange={onSelect}
                      optionLabel='label'
                      optionValue='value' disabled={disabled || isLoading}/>
            <ValidationError
                message={validationMessage}
                show={isInvalid}/>
        </>
    );
};