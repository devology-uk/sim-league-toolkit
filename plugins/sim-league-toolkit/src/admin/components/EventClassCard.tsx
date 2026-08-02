import {__} from '@wordpress/i18n';
import {useEffect, useState} from '@wordpress/element';

import {Button} from 'primereact/button';
import {Card} from 'primereact/card';
import {InputNumber} from 'primereact/inputnumber';

interface EventClassCardItem {
    carClass: string;
    driverCategory?: string;
    eventClassId: number;
    game?: string;
    isInUse?: boolean;
    isSingleCarClass: boolean;
    maxEntrants: number | null;
    name: string;
    singleCarName?: string;
}

interface EventClassCardProps<TItem extends EventClassCardItem> {
    item: TItem;
    isSaving: boolean;
    onRequestDelete: (item: TItem) => void;
    onSaveMaxEntrants: (maxEntrants: number | null) => Promise<unknown>;
}

export const EventClassCard = <TItem extends EventClassCardItem, >({item, isSaving, onRequestDelete, onSaveMaxEntrants}: EventClassCardProps<TItem>) => {
    const [maxEntrants, setMaxEntrants] = useState<number | null>(item.maxEntrants);

    useEffect(() => {
        setMaxEntrants(item.maxEntrants);
    }, [item.maxEntrants]);

    const hasUnsavedChange = maxEntrants !== item.maxEntrants;

    const handleSave = () => onSaveMaxEntrants(maxEntrants);

    const footer = (
        <>
            {!item.isInUse && (
                <Button label={__('Remove', 'sim-league-toolkit')} icon='pi pi-times' severity='danger'
                        onClick={() => onRequestDelete(item)} style={{marginLeft: '1rem'}}/>)
            }
        </>
    );

    return (
        <Card title={item.name} subTitle={item.game}
              footer={footer}
              style={{margin: '1rem', maxWidth: '400px'}}>
            <table className='table-no-border'>
                <tbody>
                <tr>
                    <th scope='row'>{__('Car Class', 'sim-league-toolkit')}</th>
                    <td>{item.carClass}</td>
                </tr>
                <tr>
                    <th scope='row'>{__('Driver Category', 'sim-league-toolkit')}</th>
                    <td>{item.driverCategory}</td>
                </tr>
                <tr>
                    <th scope='row'>{__('Is Single Car Class', 'sim-league-toolkit')}</th>
                    <td>{item.isSingleCarClass ? __('Yes', 'sim-league-toolkit') : __('No', 'sim-league-toolkit')}</td>
                </tr>
                {item.isSingleCarClass &&
                    <tr>
                        <th scope='row'>{__('Car', 'sim-league-toolkit')}</th>
                        <td>{item.singleCarName}</td>
                    </tr>
                }
                <tr>
                    <th scope='row'>{__('Is In Use', 'sim-league-toolkit')}</th>
                    <td>{item.isInUse ? __('Yes', 'sim-league-toolkit') : __('No', 'sim-league-toolkit')}</td>
                </tr>
                <tr>
                    <th scope='row'>{__('Max Entrants', 'sim-league-toolkit')}</th>
                    <td>
                        <div className='max-entrants-editor flex align-items-center gap-2'>
                            <InputNumber value={maxEntrants} onValueChange={(e) => setMaxEntrants(e.value ?? null)}
                                         placeholder={__('Unlimited', 'sim-league-toolkit')} min={0}
                                         inputStyle={{width: '4rem'}}/>
                            <Button icon='pi pi-check' disabled={!hasUnsavedChange || isSaving}
                                    onClick={handleSave}
                                    aria-label={__('Save max entrants', 'sim-league-toolkit')}/>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
        </Card>
    );
};
