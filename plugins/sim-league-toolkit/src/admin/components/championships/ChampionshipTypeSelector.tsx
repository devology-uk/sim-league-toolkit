import {useState, useEffect} from '@wordpress/element';
import {__} from '@wordpress/i18n';
import {Dropdown, DropdownChangeEvent} from 'primereact/dropdown';
import {ListItem} from "../shared/ListItem";

interface ChampionshipTypeSelectorProps {
    onSelectedItemChanged: (type: string) => void;
    championshipType: string;
    disabled?: boolean;
}

export const ChampionshipTypeSelector = ({
                                             onSelectedItemChanged,
                                             championshipType = 'std',
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
            value: 'std',
            label: __('Standard', 'sim-league-toolkit')
        },
        {
            value: 'tm',
            label: __('Track Master', 'sim-league-toolkit')
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