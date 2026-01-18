import {__} from '@wordpress/i18n';
import {useState, useEffect} from '@wordpress/element';
import apiFetch from '@wordpress/api-fetch';

import {FormEvent} from 'react';
import {Calendar} from 'primereact/calendar';
import {InputText} from 'primereact/inputtext';
import {InputTextarea} from 'primereact/inputtextarea';

import {BusyIndicator} from '../shared/BusyIndicator';
import {CancelButton} from '../shared/CancelButton';
import {ChampionshipEvent} from './ChampionshipEvent';
import {Dialog} from 'primereact/dialog';
import {Game} from '../games/Game';
import {gameGetRoute, championshipEventPostRoute} from '../shared/ApiRoutes';
import {HttpMethod} from '../shared/HttpMethod';
import {RuleSetSelector} from '../rules/RuleSetSelector';
import {SaveSubmitButton} from '../shared/SaveSubmitButton';
import {TrackSelector} from '../shared/TrackSelector';
import {ValidationError} from '../shared/ValidationError';

interface NewChampionshipEventEditorProps {
    championshipId: number;
    gameId: number;
    onSaved: () => void;
    onCancelled: () => void;
}

const minDate = new Date();
minDate.setHours(0);
minDate.setMinutes(0);
minDate.setSeconds(0);
minDate.setMilliseconds(0);

const defaultTime = new Date();
defaultTime.setHours(14);
defaultTime.setMinutes(0);
defaultTime.setSeconds(0);
defaultTime.setMilliseconds(0);



export const NewChampionshipEventEditor = ({
                                               championshipId,
                                               gameId,
                                               onSaved,
                                               onCancelled
                                           }: NewChampionshipEventEditorProps) => {
    const [description, setDescription] = useState('');
    const [gameSupportsLayouts, setGameSupportsLayouts] = useState(false);
    const [isBusy, setIsBusy] = useState(false);
    const [name, setName] = useState('');
    const [ruleSetId, setRuleSetId] = useState(0);
    const [startDateTime, setStartDateTime] = useState(minDate);
    const [trackId, setTrackId] = useState(0);
    const [trackLayoutId, setTrackLayoutId] = useState(0);
    const [validationErrors, setValidationErrors] = useState<string[]>([]);

    useEffect(() => {
        apiFetch({
                     path: gameGetRoute(gameId),
                     method: 'GET'
                 }).then((r: Game) => {
            setGameSupportsLayouts(r.supportsLayouts);
        });
    }, [gameId]);

    const onSave = (e: FormEvent<HTMLFormElement>) => {
        e.preventDefault();

        if (!validate()) {
            return;
        }

        setIsBusy(true);
        const entity: ChampionshipEvent = {
            bannerImageUrl: '',
            championshipId: championshipId,
            description: description,
            isActive: false,
            isCompleted: false,
            name: name,
            ruleSetId: ruleSetId,
            startDateTime: startDateTime.toISOString(),
            trackId: trackId
        };

        if (gameSupportsLayouts) {
            entity.trackLayoutId = trackLayoutId;
        }

        apiFetch({
                     path: championshipEventPostRoute(),
                     method: HttpMethod.POST,
                     data: entity,
                 }).then(() => {
            onSaved();
            resetForm();
            setIsBusy(false);

        });
    };

    const resetForm = () => {
        setDescription('');
        setName('');
        setRuleSetId(0);
        setStartDateTime(minDate);
        setTrackId(0);
        setTrackLayoutId(0);
        setValidationErrors([]);
    };

    const validate = () => {
        const errors = [];

        if (!name || name.length < 5) {
            errors.push('name');
        }

        if (!description || description.length < 15) {
            errors.push('description');
        }

        if (trackId < 1) {
            errors.push('track');
        }

        if (gameSupportsLayouts && trackLayoutId < 1) {
            errors.push('trackLayout');
        }

        setValidationErrors(errors);
        return errors.length === 0;
    };

    return (
        <>
            <Dialog visible={true} onHide={onCancelled} header={__('New Championship Event', 'sim-league-toolkit')}>
                <BusyIndicator isBusy={isBusy}/>
                <form onSubmit={onSave} noValidate>
                    <div className='flex flex-row  align-items-stretch gap-4'>
                        <div className='flex flex-column align-items-stretch gap-2' style={{minWidth: '300px'}}>
                            <label htmlFor='championship-event-name'>{__('Name', 'sim-league-toolkit')}</label>
                            <InputText id='championship-event-name' value={name}
                                       onChange={(e) => setName(e.target.value)}
                                       placeholder={__('Enter Name', 'sim-league-toolkit')}/>
                            <ValidationError
                                message={__('A name with at least 5 characters is required', 'sim-league-toolkit')}
                                show={validationErrors.includes('name')}/>

                            <label
                                htmlFor='championship-event-description'>{__('Description',
                                                                             'sim-league-toolkit')}</label>
                            <InputTextarea id='championship-event-description' value={description}
                                           onChange={(e) => setDescription(e.target.value)}
                                           placeholder={__('Enter Brief Description', 'sim-league-toolkit')}
                                           rows={5} cols={40}/>
                            <ValidationError
                                message={__('A brief description of the event with at least 15 characters is' +
                                                ' required.', 'sim-league-toolkit')}
                                show={validationErrors.includes('description')}/>

                            <label
                                htmlFor='championship-event-start-date'>{__('Start Date',
                                                                            'sim-league-toolkit')}</label>
                            <Calendar value={startDateTime} onChange={(e) => setStartDateTime(e.value)}
                                      minDate={minDate} readOnlyInput dateFormat='D, M d yy' showTime hourFormat='24' />

                            <TrackSelector onSelectedTrackChanged={setTrackId}
                                           onSelectedTrackLayoutChanged={setTrackLayoutId} gameId={gameId}
                                           gameSupportsLayouts={gameSupportsLayouts} trackId={trackId}
                                           trackLayoutId={trackLayoutId} disabled={isBusy}
                                           isInvalid={validationErrors.includes('track') || validationErrors.includes(
                                               'trackLayout')}
                                           trackValidationMessage={__('You must select the track that will be used' +
                                                                          ' for the event.', 'sim-league-toolkit')}
                                           trackLayoutValidationMessage={__('The game supports track layouts, you' +
                                                                                ' must select a track layout that' +
                                                                                ' will be used for the event.',
                                                                            'sim-league-toolkit')}/>
                            <RuleSetSelector ruleSetId={ruleSetId}
                                             onSelectedItemChanged={setRuleSetId}
                                             disabled={isBusy}/>
                        </div>
                    </div>
                    <SaveSubmitButton disabled={isBusy} name='submitForm'/>
                    <CancelButton onCancel={onCancelled} disabled={isBusy}/>
                </form>

            </Dialog>
        </>
    );
};