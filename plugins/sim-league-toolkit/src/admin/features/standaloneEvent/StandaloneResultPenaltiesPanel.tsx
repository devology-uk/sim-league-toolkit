import {
    useStandaloneResultPenalties,
    useCreateStandaloneResultPenalty,
    useDeleteStandaloneResultPenalty,
} from '../../../features/standaloneSessionResult';
import {PenaltiesPanel} from '../../components/PenaltiesPanel';

interface StandaloneResultPenaltiesPanelProps {
    resultId: number;
}

export const StandaloneResultPenaltiesPanel = ({resultId}: StandaloneResultPenaltiesPanelProps) => {
    const {data: penalties = [], isLoading} = useStandaloneResultPenalties(resultId);
    const {mutateAsync: createPenalty} = useCreateStandaloneResultPenalty(resultId);
    const {mutateAsync: deletePenalty} = useDeleteStandaloneResultPenalty(resultId);

    return (
        <PenaltiesPanel
            penalties={penalties}
            isLoading={isLoading}
            onAdd={(data) => createPenalty(data)}
            onDelete={(id) => deletePenalty(id)}
        />
    );
};
