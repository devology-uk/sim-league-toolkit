import {AwardTrophiesPanel} from '../trophy/AwardTrophiesPanel';
import {useAwardChampionshipTrophies, useChampionshipTrophiesPreview} from '../../../features/trophy';

interface ChampionshipTrophiesProps {
    championshipId: number;
    trophiesAwarded: boolean;
}

export const ChampionshipTrophies = ({championshipId, trophiesAwarded}: ChampionshipTrophiesProps) => {
    const {data: preview, isLoading: isPreviewLoading} = useChampionshipTrophiesPreview(championshipId);
    const {mutateAsync: awardTrophies, isPending: isAwarding} = useAwardChampionshipTrophies();

    return (
        <AwardTrophiesPanel trophiesAwarded={trophiesAwarded} preview={preview} isPreviewLoading={isPreviewLoading}
                             isAwarding={isAwarding} onAward={() => awardTrophies(championshipId)}/>
    );
};
