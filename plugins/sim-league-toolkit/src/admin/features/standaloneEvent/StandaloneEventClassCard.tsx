import {__} from '@wordpress/i18n';
import {useEffect, useState} from '@wordpress/element';

import {Button} from 'primereact/button';
import {Card} from 'primereact/card';
import {InputNumber} from 'primereact/inputnumber';

import {StandaloneEventClass, useUpdateStandaloneEventClass} from '../../../features/standaloneEvent';

interface StandaloneEventClassCardProps {
    item: StandaloneEventClass;
    onRequestDelete: (item: StandaloneEventClass) => void;
}

export const StandaloneEventClassCard = ({item, onRequestDelete}: StandaloneEventClassCardProps) => {
    const {mutateAsync: updateStandaloneEventClass, isPending: isSaving} = useUpdateStandaloneEventClass(item.standaloneEventId);

    const [maxEntrants, setMaxEntrants] = useState<number | null>(item.maxEntrants);

    useEffect(() => {
        setMaxEntrants(item.maxEntrants);
    }, [item.maxEntrants]);

    const hasUnsavedChange = maxEntrants !== item.maxEntrants;

    const onSaveMaxEntrants = async () => {
        await updateStandaloneEventClass({eventClassId: item.eventClassId, maxEntrants});
    };

    const footer = (
        <>
            {!item.isInUse && (
                <Button label={__('Remove', 'sim-league-toolkit')} icon='pi pi-times' severity='danger'
                        onClick={() => onRequestDelete(item)} style={{marginLeft: '1rem'}}/>)
            }
        </>
    );
    return (
        <>
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
                                        onClick={onSaveMaxEntrants}
                                        aria-label={__('Save max entrants', 'sim-league-toolkit')}/>
                            </div>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </Card>
        </>
    );
};
