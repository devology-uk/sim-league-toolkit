import {__} from '@wordpress/i18n';
import {useState} from '@wordpress/element';

import {Button} from 'primereact/button';
import {DataView} from 'primereact/dataview';

import {ChampionshipClass} from '../../types/ChampionshipClass';
import {ChampionshipClassCard} from './ChampionshipClassCard';
import {EventClassSelector} from '../eventClasses/EventClassSelector';
import {useChampionshipClasses} from '../../hooks/useChampionshipClasses';
import {ChampionshipClassFormData} from '../../types/ChampionshipClassFormData';

interface ChampionshipClassesProps {
    championshipId: number,
    gameId: number,
}

export const ChampionshipClasses = ({championshipId, gameId}: ChampionshipClassesProps) => {
    const {championshipClasses, createChampionshipClass, deleteChampionshipClass, isLoading} = useChampionshipClasses(
        championshipId);

    const [selectedEventClassId, setSelectedEventClassId] = useState(0);

    const onDelete = async (item: ChampionshipClass) => {
        await deleteChampionshipClass(item.eventClassId);
    };

    const onSelectEventClass = (itemId: number) => {
        setSelectedEventClassId(itemId);
    };

    const onAssignEventClass = async (): Promise<void> => {
        const formData: ChampionshipClassFormData = {
            eventClassId: selectedEventClassId,
        };

        await createChampionshipClass(championshipId, formData);
    };

    const itemTemplate = (item: ChampionshipClass) => {
        return <ChampionshipClassCard championshipClass={item} key={item.eventClassId}
                                      onRequestDelete={onDelete}/>;
    };

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
                <EventClassSelector onSelectedItemChanged={onSelectEventClass} eventClassId={selectedEventClassId}
                                    gameId={gameId}/>
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