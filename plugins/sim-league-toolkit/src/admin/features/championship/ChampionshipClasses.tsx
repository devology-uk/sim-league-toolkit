import {__} from '@wordpress/i18n';
import {useState} from '@wordpress/element';

import {Button} from 'primereact/button';
import {DataView} from 'primereact/dataview';

import {AvailableEventClassSelector} from './AvailableEventClassSelector';
import {ChampionshipClass, ChampionshipClassFormData, useChampionshipClasses, useCreateChampionshipClass, useDeleteChampionshipClass} from '../../../features/championship';
import {ChampionshipClassCard} from './ChampionshipClassCard';

interface ChampionshipClassesProps {
    championshipId: number
}

export const ChampionshipClasses = ({championshipId}: ChampionshipClassesProps) => {
    const {data: championshipClasses = [], isLoading} = useChampionshipClasses(championshipId);
    const {mutateAsync: createChampionshipClass} = useCreateChampionshipClass(championshipId);
    const {mutateAsync: deleteChampionshipClass} = useDeleteChampionshipClass(championshipId);

    const [selectedEventClassId, setSelectedEventClassId] = useState(0);
    const [reloadAvailableEvents, setReloadAvailableEvents] = useState<boolean>(false);

    const onDelete = async (item: ChampionshipClass) => {
        await deleteChampionshipClass(item.eventClassId);
        triggerReload();
    };

    const onSelectEventClass = (itemId: number) => {
        setSelectedEventClassId(itemId);
    };

    const onAssignEventClass = async (): Promise<void> => {
        const formData: ChampionshipClassFormData = {
            eventClassId: selectedEventClassId,
        };

        await createChampionshipClass(formData);
        triggerReload();
    };

    const itemTemplate = (item: ChampionshipClass) => {
        return <ChampionshipClassCard championshipClass={item} key={item.eventClassId}
                                      onRequestDelete={onDelete}/>;
    };

    const triggerReload = () => {
        setReloadAvailableEvents(true);

        setTimeout(() => setReloadAvailableEvents(false), 3000);
    }

    return (
        <>
            <p>
                {__('Sim League Toolkit allows you to create multiclass championships.  Here you can assign event classes to the championship.',
                    'sim-league-toolkit')}
            </p>
            <p>
                {__('When they enter a championship members are prompted to select which class they wish to enter.  You will be able to change that in the entrants section.',
                    'sim-league-toolkit')}
            </p>

            <div>
                <AvailableEventClassSelector onSelectedItemChanged={onSelectEventClass} championshipId={championshipId} reload={reloadAvailableEvents} />
                <Button onClick={onAssignEventClass} disabled={isLoading || selectedEventClassId === 0}
                        icon='pi pi-plus' size='small'/>
            </div>

            <DataView value={championshipClasses} itemTemplate={itemTemplate} layout='grid'
                      header={__('Assigned Classes', 'sim-league-toolkit')}
                      emptyMessage={__('No classes have been assigned.', 'sim-league-toolkit')}
                      style={{marginRight: '1rem'}}/>

        </>
    );
};