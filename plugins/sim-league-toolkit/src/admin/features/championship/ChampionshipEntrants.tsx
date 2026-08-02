import {
    ChampionshipEntry,
    useChampionshipClasses,
    useChampionshipEntries,
    useCreateChampionshipEntry,
    useDeleteChampionshipEntry,
} from '../../../features/championship';
import {useMembers} from '../../../features/member';
import {EntrantFormData, EntrantsPanel} from '../../components/EntrantsPanel';
import {ChampionshipEntrantCard} from './ChampionshipEntrantCard';

interface ChampionshipEntrantsProps {
    championshipId: number;
    gameId: number;
}

export const ChampionshipEntrants = ({championshipId, gameId}: ChampionshipEntrantsProps) => {
    const {data: entries, isLoading: entriesLoading} = useChampionshipEntries(championshipId);
    const {data: members = [], isLoading: membersLoading} = useMembers();
    const {data: championshipClasses = [], isLoading: classesLoading} = useChampionshipClasses(championshipId);
    const {mutateAsync: createEntry, isPending: isCreating} = useCreateChampionshipEntry(championshipId);
    const {mutateAsync: deleteEntry} = useDeleteChampionshipEntry(championshipId);

    const isLoading = entriesLoading || membersLoading || isCreating;

    const onAdd = (formData: EntrantFormData) => createEntry(formData);

    const onDelete = async (entry: ChampionshipEntry) => {
        await deleteEntry(entry.id);
    };

    return (
        <EntrantsPanel<ChampionshipEntry>
            gameId={gameId}
            entries={entries}
            isLoading={isLoading}
            classes={championshipClasses}
            classesLoading={classesLoading}
            members={members}
            onAdd={onAdd}
            renderCard={(entry) => <ChampionshipEntrantCard key={entry.id} entry={entry} onRequestDelete={onDelete}/>}
        />
    );
};
