import {SessionResultFormValues, SessionResultsGrid} from '../sessionResult/SessionResultsGrid';
import {StandaloneResultPenaltiesPanel} from './StandaloneResultPenaltiesPanel';
import {useStandaloneEventEntries} from '../../../features/standaloneEvent';
import {
    useCreateStandaloneSessionResult,
    useDeleteStandaloneSessionResult,
    useStandaloneSessionResults,
    useUpdateStandaloneSessionResult,
} from '../../../features/standaloneSessionResult';

interface StandaloneSessionResultsProps {
    standaloneEventId: number;
    eventSessionId: number;
}

export const StandaloneSessionResults = ({standaloneEventId, eventSessionId}: StandaloneSessionResultsProps) => {
    const {data: entries = [], isLoading: isLoadingEntries} = useStandaloneEventEntries(standaloneEventId);
    const {data: results = [], isLoading: isLoadingResults} = useStandaloneSessionResults(eventSessionId);
    const {mutateAsync: createResult} = useCreateStandaloneSessionResult(eventSessionId);
    const {mutateAsync: updateResult} = useUpdateStandaloneSessionResult(eventSessionId);
    const {mutateAsync: deleteResult} = useDeleteStandaloneSessionResult(eventSessionId);

    const entrants = entries
        .filter((entry) => entry.status === 'confirmed')
        .map((entry) => ({
            entryId: entry.id,
            memberName: entry.memberName,
            raceNumber: entry.raceNumber,
            className: entry.className || null,
        }));

    const gridResults = results.map((result) => ({
        id: result.id,
        entryId: result.standaloneEventEntryId,
        position: result.position,
        totalTimeMs: result.totalTimeMs,
        fastestLapMs: result.fastestLapMs,
        sector1TimeMs: result.sector1TimeMs,
        sector2TimeMs: result.sector2TimeMs,
        sector3TimeMs: result.sector3TimeMs,
        lapsCompleted: result.lapsCompleted,
        status: result.status,
        points: result.points,
    }));

    const handleSaveResult = async (entryId: number, resultId: number | undefined, data: SessionResultFormValues) => {
        if (resultId) {
            await updateResult({id: resultId, data: {standaloneEventEntryId: entryId, ...data}});
        } else {
            await createResult({standaloneEventEntryId: entryId, ...data});
        }
    };

    return (
        <SessionResultsGrid
            entrants={entrants}
            results={gridResults}
            isLoading={isLoadingEntries || isLoadingResults}
            onSaveResult={handleSaveResult}
            onDeleteResult={deleteResult}
            renderPenaltyPanel={(resultId) => <StandaloneResultPenaltiesPanel resultId={resultId}/>}
        />
    );
};
