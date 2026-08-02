import {
    useChampionshipResultPenalties,
    useCreateChampionshipResultPenalty,
    useDeleteChampionshipResultPenalty,
} from '../../../features/championshipSessionResult';
import {PenaltiesPanel} from '../../components/PenaltiesPanel';

interface ChampionshipResultPenaltiesPanelProps {
    resultId: number;
}

export const ChampionshipResultPenaltiesPanel = ({resultId}: ChampionshipResultPenaltiesPanelProps) => {
    const {data: penalties = [], isLoading} = useChampionshipResultPenalties(resultId);
    const {mutateAsync: createPenalty} = useCreateChampionshipResultPenalty(resultId);
    const {mutateAsync: deletePenalty} = useDeleteChampionshipResultPenalty(resultId);

    return (
        <PenaltiesPanel
            penalties={penalties}
            isLoading={isLoading}
            onAdd={(data) => createPenalty(data)}
            onDelete={(id) => deletePenalty(id)}
        />
    );
};
