import {__} from '@wordpress/i18n';
import {useState} from '@wordpress/element';

import {Button} from 'primereact/button';
import {InputNumber} from 'primereact/inputnumber';
import {InputText} from 'primereact/inputtext';

import {
    useChampionshipResultPenalties,
    useCreateChampionshipResultPenalty,
    useDeleteChampionshipResultPenalty,
} from '../../../features/championshipSessionResult';

interface ChampionshipResultPenaltiesPanelProps {
    resultId: number;
}

export const ChampionshipResultPenaltiesPanel = ({resultId}: ChampionshipResultPenaltiesPanelProps) => {
    const {data: penalties = [], isLoading} = useChampionshipResultPenalties(resultId);
    const {mutateAsync: createPenalty} = useCreateChampionshipResultPenalty(resultId);
    const {mutateAsync: deletePenalty} = useDeleteChampionshipResultPenalty(resultId);

    const [reason, setReason] = useState('');
    const [penaltySeconds, setPenaltySeconds] = useState<number | null>(null);

    const handleAdd = async () => {
        if (!reason.trim()) {
            return;
        }

        await createPenalty({reason: reason.trim(), penaltySeconds});
        setReason('');
        setPenaltySeconds(null);
    };

    return (
        <div className='flex flex-column gap-2'>
            {isLoading && <span>{__('Loading penalties…', 'sim-league-toolkit')}</span>}
            {!isLoading && penalties.length === 0 &&
                <span>{__('No penalties recorded.', 'sim-league-toolkit')}</span>}
            {penalties.map((penalty) => (
                <div key={penalty.id} className='flex align-items-center gap-2'>
                    <span style={{flexGrow: 1}}>{penalty.reason}</span>
                    <span>{penalty.penaltySeconds !== null ? `+${penalty.penaltySeconds}s` : ''}</span>
                    <Button icon='pi pi-trash' rounded text severity='danger'
                            onClick={() => deletePenalty(penalty.id as number)}/>
                </div>
            ))}
            <div className='flex align-items-center gap-2'>
                <InputText value={reason} placeholder={__('Reason', 'sim-league-toolkit')}
                           onChange={(e) => setReason(e.target.value)}/>
                <InputNumber value={penaltySeconds} placeholder={__('Seconds', 'sim-league-toolkit')}
                             onChange={(e) => setPenaltySeconds(e.value ?? null)}/>
                <Button icon='pi pi-plus' rounded text onClick={handleAdd}
                        tooltip={__('Add penalty', 'sim-league-toolkit')}/>
            </div>
        </div>
    );
};
