import {ChampionshipClass, useUpdateChampionshipClass} from '../../../features/championship';
import {EventClassCard} from '../../components/EventClassCard';

interface ChampionshipClassCardProps {
    championshipClass: ChampionshipClass;
    onRequestDelete: (item: ChampionshipClass) => void;
}

export const ChampionshipClassCard = ({championshipClass, onRequestDelete}: ChampionshipClassCardProps) => {
    const {mutateAsync: updateChampionshipClass, isPending: isSaving} = useUpdateChampionshipClass(championshipClass.championshipId);

    const onSaveMaxEntrants = (maxEntrants: number | null) =>
        updateChampionshipClass({eventClassId: championshipClass.eventClassId, maxEntrants});

    return (
        <EventClassCard
            item={championshipClass}
            isSaving={isSaving}
            onRequestDelete={onRequestDelete}
            onSaveMaxEntrants={onSaveMaxEntrants}
        />
    );
};
