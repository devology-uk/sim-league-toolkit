import {__} from '@wordpress/i18n';
import {useEffect, useState} from '@wordpress/element';
import apiFetch from '@wordpress/api-fetch';

import {Dialog} from 'primereact/dialog';
import {SaveSubmitButton} from '../shared/SaveSubmitButton';
import {CancelButton} from '../shared/CancelButton';
import {BusySpinner} from '../shared/BusySpinner';
import {InputText} from 'primereact/inputtext';
import {ValidationError} from '../shared/ValidationError';
import {InputTextarea} from 'primereact/inputtextarea';
import {InputNumber} from 'primereact/inputnumber';

export const ScoringSetEditor = ({show, onSaved, onCancelled, scoringSetId = 0}) => {

    const [description, setDescription] = useState('');
    const [isBusy, setIsBusy] = useState(false);
    const [name, setName] = useState('');
    const [pointsForFastestLap, setPointsForFastestLap] = useState(0);
    const [pointsForFinishing, setPointsForFinishing] = useState(0);
    const [pointsForPole, setPointsForPole] = useState(0);
    const [validationErrors, setValidationErrors] = useState([]);

    useEffect(() => {
        if (scoringSetId === 0) {
            return;
        }

        apiFetch({
            path: `/sltk/v1/scoring-set/${scoringSetId}`,
            method: 'GET',
        }).then((r) => {
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
        const scoringSet = {
            name: name,
            description: description,
            pointsForFastestLap: pointsForFastestLap,
            pointsForFinishing: pointsForFinishing,
            pointsForPole: pointsForPole
        };

        if (scoringSetId && scoringSetId > 0) {
            scoringSet.id = scoringSetId;
        }

        apiFetch({
            path: '/sltk/v1/scoring-set',
            method: 'POST',
            data: scoringSet,
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
                <Dialog visible={show} onHide={onCancelled} header={__('Event Class', 'sim-league-toolkit')}>
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

                                <label htmlFor='scoring-set-description'>{__('Description', 'sim-league-toolkit')}</label>
                                <InputTextarea id='scoring-set-description' value={description} onChange={(e) => setDescription(e.target.value)}
                                               placeholder={__('Enter Brief Description', 'sim-league-toolkit')}
                                               rows={5} cols={40}/>
                                <ValidationError
                                    message={__('A brief description of the scoring set with at least 15 characters is required.', 'sim-league-toolkit')}
                                    show={validationErrors.includes('description')}/>

                                <label htmlFor='scoring-set-points-for-fastest-lap'>{__('Points for Fastest Lap', 'sim-league-toolkit')}</label>
                                <InputNumber id='scoring-set-points-for-fastest-lap' value={pointsForFastestLap}
                                           onChange={(e) => setPointsForFastestLap(e.value)}
                                             min={0}
                                           placeholder={__('Points for Fastest Lap', 'sim-league-toolkit')}/>
                                <ValidationError
                                    message={__('Points for fastest lap cannot be less than zero', 'sim-league-toolkit')}
                                    show={validationErrors.includes('pointsForFastestLap')}/>

                                <label htmlFor='scoring-set-points-for-finishing'>{__('Points for Finishing', 'sim-league-toolkit')}</label>
                                <InputNumber id='scoring-set-points-for-finishing' value={pointsForFinishing}
                                             onChange={(e) => setPointsForFinishing(e.value)}
                                             min={0}
                                             placeholder={__('Points for Finishing', 'sim-league-toolkit')}/>
                                <ValidationError
                                    message={__('Points for finishing cannot be less than zero', 'sim-league-toolkit')}
                                    show={validationErrors.includes('pointsForFinishing')}/>

                                <label htmlFor='scoring-set-points-for-pole'>{__('Points for Pole', 'sim-league-toolkit')}</label>
                                <InputNumber id='scoring-set-points-for-pole' value={pointsForPole}
                                             onChange={(e) => setPointsForPole(e.value)}
                                             min={0}
                                             placeholder={__('Points for Pole', 'sim-league-toolkit')}/>
                                <ValidationError
                                    message={__('Points for pole cannot be less than zero', 'sim-league-toolkit')}
                                    show={validationErrors.includes('pointsForPole')}/>

                            </div>
                        </div>
                        <SaveSubmitButton disable={isBusy} name='submitRuleSet'/>
                        <CancelButton onCancel={onCancelled} disabled={isBusy}/>
                    </form>
                    <BusySpinner isActive={isBusy}/>
                </Dialog>
            )}
        </>
    )
}