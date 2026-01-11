import {__} from '@wordpress/i18n';
import {useState, useEffect} from '@wordpress/element';

import {Dropdown, DropdownChangeEvent} from 'primereact/dropdown';

import {ChampionshipTypes, translateChampionshipType} from "../shared/ChampionshipTypes";
import {ListItem} from "../shared/ListItem";

interface ChampionshipTypeSelectorProps {
    onSelectedItemChanged: (type: ChampionshipTypes) => void;
    championshipType: number;
    disabled?: boolean;
}

export const ChampionshipTypeSelector = ({
                                             onSelectedItemChanged,
                                             championshipType = ChampionshipTypes.Standard,
                                             disabled = false
                                         }: ChampionshipTypeSelectorProps) => {

    const [selectedItem, setSelectedItem] = useState(championshipType);

    useEffect(() => {
        setSelectedItem(championshipType);
    }, [championshipType])

    const onSelect = (e: DropdownChangeEvent) => {
        setSelectedItem(e.target.value);
        onSelectedItemChanged(e.target.value);
    }

    const listItems: ListItem[] = [
        {
            value: ChampionshipTypes.Standard,
            label: translateChampionshipType(ChampionshipTypes.Standard)
        },
        {
            value: ChampionshipTypes.Trackmaster,
            label: translateChampionshipType(ChampionshipTypes.Trackmaster)
        }
    ];

    return (
        <>
            <label htmlFor='rule-set-selector'>{__('Championship Type', 'sim-league-toolkit')}</label>
            <Dropdown id='rule-set-selector' value={selectedItem} options={listItems} onChange={onSelect}
                      optionLabel='label'
                      optionValue='value' disabled={disabled}/>
        </>
    )
}