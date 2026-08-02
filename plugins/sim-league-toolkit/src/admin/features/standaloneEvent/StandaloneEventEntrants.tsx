import {
    StandaloneEventEntry,
    useStandaloneEventEntries,
    useStandaloneEventClasses,
    useCreateStandaloneEventEntry,
    useDeleteStandaloneEventEntry,
} from '../../../features/standaloneEvent';
import {useMembers} from '../../../features/member';
import {EntrantFormData, EntrantsPanel} from '../../components/EntrantsPanel';
import {StandaloneEventEntrantCard} from './StandaloneEventEntrantCard';

interface StandaloneEventEntrantsProps {
    standaloneEventId: number;
    gameId: number;
}

export const StandaloneEventEntrants = ({standaloneEventId, gameId}: StandaloneEventEntrantsProps) => {
    const {data: entries, isLoading: entriesLoading} = useStandaloneEventEntries(standaloneEventId);
    const {data: members = [], isLoading: membersLoading} = useMembers();
    const {data: eventClasses = [], isLoading: classesLoading} = useStandaloneEventClasses(standaloneEventId);
    const {mutateAsync: createEntry, isPending: isCreating} = useCreateStandaloneEventEntry(standaloneEventId);
    const {mutateAsync: deleteEntry} = useDeleteStandaloneEventEntry(standaloneEventId);

    const isLoading = entriesLoading || membersLoading || isCreating;

    const onAdd = (formData: EntrantFormData) => createEntry(formData);

    const onDelete = async (entry: StandaloneEventEntry) => {
        await deleteEntry(entry.id);
    };

    return (
        <EntrantsPanel<StandaloneEventEntry>
            gameId={gameId}
            entries={entries}
            isLoading={isLoading}
            classes={eventClasses}
            classesLoading={classesLoading}
            members={members}
            onAdd={onAdd}
            renderCard={(entry) => <StandaloneEventEntrantCard key={entry.id} entry={entry} onRequestDelete={onDelete}/>}
        />
    );
};
