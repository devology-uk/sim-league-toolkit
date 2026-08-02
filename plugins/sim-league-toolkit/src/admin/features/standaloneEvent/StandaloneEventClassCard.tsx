import {StandaloneEventClass, useUpdateStandaloneEventClass} from '../../../features/standaloneEvent';
import {EventClassCard} from '../../components/EventClassCard';

interface StandaloneEventClassCardProps {
    item: StandaloneEventClass;
    onRequestDelete: (item: StandaloneEventClass) => void;
}

export const StandaloneEventClassCard = ({item, onRequestDelete}: StandaloneEventClassCardProps) => {
    const {mutateAsync: updateStandaloneEventClass, isPending: isSaving} = useUpdateStandaloneEventClass(item.standaloneEventId);

    const onSaveMaxEntrants = (maxEntrants: number | null) =>
        updateStandaloneEventClass({eventClassId: item.eventClassId, maxEntrants});

    return (
        <EventClassCard
            item={item}
            isSaving={isSaving}
            onRequestDelete={onRequestDelete}
            onSaveMaxEntrants={onSaveMaxEntrants}
        />
    );
};
