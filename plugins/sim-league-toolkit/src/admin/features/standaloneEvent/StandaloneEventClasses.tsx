import {__} from '@wordpress/i18n';
import {useState} from '@wordpress/element';

import {Button} from 'primereact/button';
import {DataView} from 'primereact/dataview';

import {AvailableStandaloneEventClassSelector} from './AvailableStandaloneEventClassSelector';
import {StandaloneEventClass, StandaloneEventClassFormData, useStandaloneEventClasses, useCreateStandaloneEventClass, useDeleteStandaloneEventClass} from '../../../features/standaloneEvent';
import {StandaloneEventClassCard} from './StandaloneEventClassCard';

interface StandaloneEventClassesProps {
    standaloneEventId: number;
}

export const StandaloneEventClasses = ({standaloneEventId}: StandaloneEventClassesProps) => {
    const {data: eventClasses = [], isLoading} = useStandaloneEventClasses(standaloneEventId);
    const {mutateAsync: createEventClass} = useCreateStandaloneEventClass(standaloneEventId);
    const {mutateAsync: deleteEventClass} = useDeleteStandaloneEventClass(standaloneEventId);

    const [selectedEventClassId, setSelectedEventClassId] = useState(0);
    const [reloadAvailable, setReloadAvailable] = useState<boolean>(false);

    const onDelete = async (item: StandaloneEventClass) => {
        await deleteEventClass(item.eventClassId);
        triggerReload();
    };

    const onSelectEventClass = (itemId: number) => {
        setSelectedEventClassId(itemId);
    };

    const onAssignEventClass = async (): Promise<void> => {
        const formData: StandaloneEventClassFormData = {
            eventClassId: selectedEventClassId,
        };

        await createEventClass(formData);
        triggerReload();
    };

    const itemTemplate = (item: StandaloneEventClass) => {
        return <StandaloneEventClassCard item={item} key={item.eventClassId}
                                        onRequestDelete={onDelete}/>;
    };

    const triggerReload = () => {
        setReloadAvailable(true);

        setTimeout(() => setReloadAvailable(false), 3000);
    };

    return (
        <>
            <p>
                {__('Here you can assign event classes to this standalone event.',
                    'sim-league-toolkit')}
            </p>
            <p>
                {__('When entrants are added you will be able to select which class they are entering.',
                    'sim-league-toolkit')}
            </p>

            <div>
                <AvailableStandaloneEventClassSelector onSelectedItemChanged={onSelectEventClass}
                                                       standaloneEventId={standaloneEventId}
                                                       reload={reloadAvailable}/>
                <Button onClick={onAssignEventClass} disabled={isLoading || selectedEventClassId === 0}
                        icon='pi pi-plus' size='small'/>
            </div>

            <DataView value={eventClasses} itemTemplate={itemTemplate} layout='grid'
                      header={__('Assigned Classes', 'sim-league-toolkit')}
                      emptyMessage={__('No classes have been assigned.', 'sim-league-toolkit')}
                      style={{marginRight: '1rem'}}/>
        </>
    );
};
