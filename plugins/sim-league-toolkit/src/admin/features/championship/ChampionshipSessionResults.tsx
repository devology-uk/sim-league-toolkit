import {SessionResultFormValues, SessionResultsGrid} from '../sessionResult/SessionResultsGrid';
import {ChampionshipResultPenaltiesPanel} from './ChampionshipResultPenaltiesPanel';
import {useChampionshipEntries} from '../../../features/championship';
import {
    useChampionshipSessionResults,
    useCreateChampionshipSessionResult,
    useDeleteChampionshipSessionResult,
    useUpdateChampionshipSessionResult,
} from '../../../features/championshipSessionResult';

interface ChampionshipSessionResultsProps {
    championshipId: number;
    eventSessionId: number;
}

export const ChampionshipSessionResults = ({championshipId, eventSessionId}: ChampionshipSessionResultsProps) => {
    const {data: entries = [], isLoading: isLoadingEntries} = useChampionshipEntries(championshipId);
    const {data: results = [], isLoading: isLoadingResults} = useChampionshipSessionResults(eventSessionId);
    const {mutateAsync: createResult} = useCreateChampionshipSessionResult(eventSessionId);
    const {mutateAsync: updateResult} = useUpdateChampionshipSessionResult(eventSessionId);
    const {mutateAsync: deleteResult} = useDeleteChampionshipSessionResult(eventSessionId);

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
        entryId: result.championshipEntryId,
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
            await updateResult({id: resultId, data: {championshipEntryId: entryId, ...data}});
        } else {
            await createResult({championshipEntryId: entryId, ...data});
        }
    };

    return (
        <SessionResultsGrid
            entrants={entrants}
            results={gridResults}
            isLoading={isLoadingEntries || isLoadingResults}
            onSaveResult={handleSaveResult}
            onDeleteResult={deleteResult}
            renderPenaltyPanel={(resultId) => <ChampionshipResultPenaltiesPanel resultId={resultId}/>}
        />
    );
};
