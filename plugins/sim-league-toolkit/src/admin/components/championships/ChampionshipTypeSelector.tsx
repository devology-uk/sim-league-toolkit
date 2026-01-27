import {__} from '@wordpress/i18n';
import {useState, useEffect} from '@wordpress/element';

import {Dropdown, DropdownChangeEvent} from 'primereact/dropdown';

import {ListItem} from "../../types/ListItem";
import {ChampionshipType, ChampionshipTypeLabels} from '../../types/generated/ChampionshipType';

interface ChampionshipTypeSelectorProps {
    onSelectedItemChanged: (type: ChampionshipType) => void;
    championshipType: ChampionshipType;
    disabled?: boolean;
}

export const ChampionshipTypeSelector = ({
                                             onSelectedItemChanged,
                                             championshipType = ChampionshipType.STANDARD,
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
            value: ChampionshipType.STANDARD,
            label: ChampionshipTypeLabels.standard
        },
        {
            value: ChampionshipType.TRACK_MASTER,
            label: ChampionshipTypeLabels.trackMaster
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