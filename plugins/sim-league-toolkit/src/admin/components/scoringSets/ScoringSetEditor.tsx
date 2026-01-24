import {__} from '@wordpress/i18n';
import apiFetch from '@wordpress/api-fetch';
import {useEffect, useState} from '@wordpress/element';

import {Dialog} from 'primereact/dialog';
import {InputNumber} from 'primereact/inputnumber';
import {InputText} from 'primereact/inputtext';
import {InputTextarea} from 'primereact/inputtextarea';

import {BusyIndicator} from "../shared/BusyIndicator";
import {CancelButton} from '../shared/CancelButton';
import {HttpMethod} from '../../enums/HttpMethod';
import {SaveSubmitButton} from '../shared/SaveSubmitButton';
import {ScoreList} from './ScoreList';
import {ScoringSet} from "../../types/ScoringSet";
import {scoringSetGetRoute, scoringSetPostRoute} from '../../api/endpoints/scoringSetsApiRoutes';
import {ValidationError} from '../shared/ValidationError';

interface ScoringSetEditorProps {
    show: boolean;
    onSaved: () => void;
    onCancelled: () => void;
    scoringSetId?: number;
}

export const ScoringSetEditor = ({show, onSaved, onCancelled, scoringSetId = 0}: ScoringSetEditorProps) => {

    const [description, setDescription] = useState('');
    const [isBusy, setIsBusy] = useState(false);
    const [name, setName] = useState('');
    const [pointsForFastestLap, setPointsForFastestLap] = useState(0);
    const [pointsForFinishing, setPointsForFinishing] = useState(0);
    const [pointsForPole, setPointsForPole] = useState(0);
    const [validationErrors, setValidationErrors] = useState<string[]>([]);

    useEffect(() => {
        if (scoringSetId === 0) {
            return;
        }

        apiFetch({
            path: scoringSetGetRoute(scoringSetId),
            method: HttpMethod.GET,
        }).then((r: ScoringSet ) => {
            setDescription(r.description);
            setName(r.name);
            setPointsForFastestLap(r.pointsForFastestLap);
            setPointsForFinishing(r.pointsForFinishing);
            setPointsForPole(r.pointsForPole);
            setIsBusy(false);
        });
    }, [scoringSetId]);

    const resetForm = () => {
        setDescription('');
        setName('');
        setPointsForFastestLap(0);
        setPointsForFinishing(0);
        setPointsForPole(0);
    }

    const onSave = (evt) => {
        evt.preventDefault();

        if (!validate()) {
            return;
        }

        setIsBusy(true);
        const entity: ScoringSet = {
            name: name,
            description: description,
            pointsForFastestLap: pointsForFastestLap,
            pointsForFinishing: pointsForFinishing,
            pointsForPole: pointsForPole
        };

        if (scoringSetId && scoringSetId > 0) {
            entity.id = scoringSetId;
        }

        apiFetch({
            path: scoringSetPostRoute(),
            method: HttpMethod.POST,
            data: entity,
        }).then(() => {
            onSaved();

            resetForm();
            setIsBusy(false);
        });
    }

    const validate = () => {
        const errors = [];

        if (!name || name.length < 2) {
            errors.push('name');
        }

        if (!description || description.length < 15) {
            errors.push('description');
        }

        if (pointsForFastestLap < 0) {
            errors.push('pointsForFastestLaps');
        }

        if (pointsForFinishing < 0) {
            errors.push('pointsForFinishing');
        }

        if (pointsForPole < 0) {
            errors.push('pointsForPole');
        }

        setValidationErrors(errors);
        return errors.length === 0;
    }

    return (
        <>
            {show && (
                <Dialog visible={show} onHide={onCancelled} header={__('Scoring Set', 'sim-league-toolkit')}>
                    <BusyIndicator isBusy={isBusy} />
                    <form onSubmit={onSave} noValidate>
                        <div className='flex flex-row  align-items-stretch gap-4'>
                            <div className='flex flex-column align-items-stretch gap-2' style={{minWidth: '300px'}}>
                                <label htmlFor='scoring-set-name'>{__('Name', 'sim-league-toolkit')}</label>
                                <InputText id='scoring-set-name' value={name}
                                           onChange={(e) => setName(e.target.value)}
                                           placeholder={__('Enter Name', 'sim-league-toolkit')}/>
                                <ValidationError
                                    message={__('A name with at least 2 characters is required', 'sim-league-toolkit')}
                                    show={validationErrors.includes('name')}/>

                                <label
                                    htmlFor='scoring-set-description'>{__('Description', 'sim-league-toolkit')}</label>
                                <InputTextarea id='scoring-set-description' value={description}
                                               onChange={(e) => setDescription(e.target.value)}
                                               placeholder={__('Enter Brief Description', 'sim-league-toolkit')}
                                               rows={5} cols={40}/>
                                <ValidationError
                                    message={__('A brief description of the scoring set with at least 15 characters is required.', 'sim-league-toolkit')}
                                    show={validationErrors.includes('description')}/>

                                <label
                                    htmlFor='scoring-set-points-for-fastest-lap'>{__('Points for Fastest Lap', 'sim-league-toolkit')}</label>
                                <InputNumber id='scoring-set-points-for-fastest-lap' value={pointsForFastestLap}
                                             onChange={(e) => setPointsForFastestLap(e.value)}
                                             min={0}
                                             placeholder={__('Points for Fastest Lap', 'sim-league-toolkit')}/>
                                <ValidationError
                                    message={__('Points for fastest lap cannot be less than zero', 'sim-league-toolkit')}
                                    show={validationErrors.includes('pointsForFastestLap')}/>

                                <label
                                    htmlFor='scoring-set-points-for-finishing'>{__('Points for Finishing', 'sim-league-toolkit')}</label>
                                <InputNumber id='scoring-set-points-for-finishing' value={pointsForFinishing}
                                             onChange={(e) => setPointsForFinishing(e.value)}
                                             min={0}
                                             placeholder={__('Points for Finishing', 'sim-league-toolkit')}/>
                                <ValidationError
                                    message={__('Points for finishing cannot be less than zero', 'sim-league-toolkit')}
                                    show={validationErrors.includes('pointsForFinishing')}/>

                                <label
                                    htmlFor='scoring-set-points-for-pole'>{__('Points for Pole', 'sim-league-toolkit')}</label>
                                <InputNumber id='scoring-set-points-for-pole' value={pointsForPole}
                                             onChange={(e) => setPointsForPole(e.value)}
                                             min={0}
                                             placeholder={__('Points for Pole', 'sim-league-toolkit')}/>
                                <ValidationError
                                    message={__('Points for pole cannot be less than zero', 'sim-league-toolkit')}
                                    show={validationErrors.includes('pointsForPole')}/>

                            </div>
                        </div>
                        <SaveSubmitButton disabled={isBusy} name='submitRuleSet'/>
                        <CancelButton onCancel={onCancelled} disabled={isBusy}/>
                    </form>
                    {scoringSetId > 0 && (<ScoreList scoringSetId={scoringSetId}/>)}
                </Dialog>
            )}
        </>
    )
}