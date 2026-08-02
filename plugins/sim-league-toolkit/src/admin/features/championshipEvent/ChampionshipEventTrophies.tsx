import {AwardTrophiesPanel} from '../trophy/AwardTrophiesPanel';
import {useAwardChampionshipEventTrophies, useChampionshipEventTrophiesPreview} from '../../../features/trophy';

interface ChampionshipEventTrophiesProps {
    championshipEventId: number;
    championshipId: number;
    trophiesAwarded: boolean;
}

export const ChampionshipEventTrophies = ({championshipEventId, championshipId, trophiesAwarded}: ChampionshipEventTrophiesProps) => {
    const {data: preview, isLoading: isPreviewLoading} = useChampionshipEventTrophiesPreview(championshipEventId);
    const {mutateAsync: awardTrophies, isPending: isAwarding} = useAwardChampionshipEventTrophies(championshipId);

    return (
        <AwardTrophiesPanel trophiesAwarded={trophiesAwarded} preview={preview} isPreviewLoading={isPreviewLoading}
                             isAwarding={isAwarding} onAward={() => awardTrophies(championshipEventId)}/>
    );
};
