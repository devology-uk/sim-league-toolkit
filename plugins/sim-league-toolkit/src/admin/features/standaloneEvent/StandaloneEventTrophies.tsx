import {AwardTrophiesPanel} from '../trophy/AwardTrophiesPanel';
import {useAwardStandaloneEventTrophies, useStandaloneEventTrophiesPreview} from '../../../features/trophy';

interface StandaloneEventTrophiesProps {
    standaloneEventId: number;
    trophiesAwarded: boolean;
}

export const StandaloneEventTrophies = ({standaloneEventId, trophiesAwarded}: StandaloneEventTrophiesProps) => {
    const {data: preview, isLoading: isPreviewLoading} = useStandaloneEventTrophiesPreview(standaloneEventId);
    const {mutateAsync: awardTrophies, isPending: isAwarding} = useAwardStandaloneEventTrophies();

    return (
        <AwardTrophiesPanel trophiesAwarded={trophiesAwarded} preview={preview} isPreviewLoading={isPreviewLoading}
                             isAwarding={isAwarding} onAward={() => awardTrophies(standaloneEventId)}/>
    );
};
