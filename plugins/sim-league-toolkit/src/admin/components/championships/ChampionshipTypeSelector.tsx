import {useState} from '@wordpress/element';
import {__} from '@wordpress/i18n';
import {Dropdown} from 'primereact/dropdown';

export const ChampionshipTypeSelector = ({onSelectedItemChanged, championshipTye = 'std', disabled = false}) => {

    const [selectedItem, setSelectedItem] = useState(championshipTye);

    const onSelect = (evt) => {
        setSelectedItem(evt.target.value);
        onSelectedItemChanged(evt.target.value);
    }

    const itemOptions = [
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
            <Dropdown id='rule-set-selector' value={selectedItem} options={itemOptions} onChange={onSelect}
                      optionLabel='label'
                      optionValue='value' disabled={disabled}/>
        </>
    )
}