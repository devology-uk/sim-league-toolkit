import {__} from '@wordpress/i18n';
import {useEffect, useState} from '@wordpress/element';
import {FormEvent} from 'react';

import {Calendar} from 'primereact/calendar';
import {Checkbox} from 'primereact/checkbox';
import {InputNumber} from 'primereact/inputnumber';
import {InputText} from 'primereact/inputtext';
import {InputTextarea} from 'primereact/inputtextarea';

import {BusyIndicator} from '../../components/BusyIndicator';
import {CancelButton} from '../../components/CancelButton';
import {SaveSubmitButton} from '../../components/SaveSubmitButton';
import {ValidationError} from '../../components/ValidationError';
import {GameSelector} from '../game/GameSelector';
import {TrackSelector} from '../game/TrackSelector';
import {RuleSetSelector} from '../ruleSet/RuleSetSelector';
import {ScoringSetSelector} from '../scoringSet/ScoringSetSelector';
import {StandaloneEventFormData, useCreateStandaloneEvent} from '../../../features/standaloneEvent';
import {useGames} from '../../../features/game';

interface NewStandaloneEventEditorProps {
    onSaved: () => void;
    onCancelled: () => void;
}

export const NewStandaloneEventEditor = ({onSaved, onCancelled}: NewStandaloneEventEditorProps) => {
    const {mutateAsync: createEvent, isPending: isLoading} = useCreateStandaloneEvent();
    const {data: games = []} = useGames();

    const [gameId, setGameId] = useState(0);
    const [gameSupportsLayouts, setGameSupportsLayouts] = useState(false);
    const [trackId, setTrackId] = useState(0);
    const [trackLayoutId, setTrackLayoutId] = useState(0);
    const [name, setName] = useState('');
    const [description, setDescription] = useState('');
    const [eventDate, setEventDate] = useState(new Date());
    const [startTimeDate, setStartTimeDate] = useState<Date | null>(null);
    const [isActive, setIsActive] = useState(false);
    const [maxEntrants, setMaxEntrants] = useState(0);
    const [scoringSetId, setScoringSetId] = useState(0);
    const [ruleSetId, setRuleSetId] = useState(0);
    const [validationErrors, setValidationErrors] = useState<string[]>([]);

    useEffect(() => {
        if (gameId < 1) {
            return;
        }
        const game = games.find(g => g.id === gameId);
        if (game) {
            setGameSupportsLayouts(game.supportsLayouts);
        }
    }, [gameId, games]);

    const resetForm = () => {
        setGameId(0);
        setGameSupportsLayouts(false);
        setTrackId(0);
        setTrackLayoutId(0);
        setName('');
        setDescription('');
        setEventDate(new Date());
        setStartTimeDate(null);
        setIsActive(false);
        setMaxEntrants(0);
        setScoringSetId(0);
        setRuleSetId(0);
        setValidationErrors([]);
    };

    const validate = () => {
        const errors: string[] = [];

        if (gameId < 1) {
            errors.push('game');
        }

        if (trackId < 1) {
            errors.push('track');
        }

        if (gameSupportsLayouts && trackLayoutId < 1) {
            errors.push('trackLayout');
        }

        if (!name || name.length < 5) {
            errors.push('name');
        }

        if (!description || description.length < 15) {
            errors.push('description');
        }

        if (scoringSetId < 1) {
            errors.push('scoringSet');
        }

        setValidationErrors(errors);
        return errors.length === 0;
    };

    const onSave = async (e: FormEvent<HTMLFormElement>) => {
        e.preventDefault();

        if (!validate()) {
            return;
        }

        const startTime = startTimeDate
            ? `${String(startTimeDate.getHours()).padStart(2, '0')}:${String(startTimeDate.getMinutes()).padStart(2, '0')}`
            : '';

        const formData: StandaloneEventFormData = {
            name,
            description,
            bannerImageUrl: '',
            gameId,
            trackId,
            eventDate,
            startTime,
            isActive,
            maxEntrants,
            scoringSetId: scoringSetId > 0 ? scoringSetId : undefined,
            ruleSetId: ruleSetId > 0 ? ruleSetId : undefined,
        };

        if (gameSupportsLayouts && trackLayoutId > 0) {
            formData.trackLayoutId = trackLayoutId;
        }

        await createEvent(formData);
        resetForm();
        onSaved();
    };

    const onSelectedGameChanged = (id: number) => {
        setTrackId(0);
        setTrackLayoutId(0);
        setGameId(id);
    };

    return (
        <>
            <BusyIndicator isBusy={isLoading}/>
            <h3>{__('New Event', 'sim-league-toolkit')}</h3>
            <form onSubmit={onSave} noValidate>
                <GameSelector gameId={gameId}
                              isInvalid={validationErrors.includes('game')}
                              validationMessage={__('You must select the game that this event will use.', 'sim-league-toolkit')}
                              onSelectedItemChanged={onSelectedGameChanged}
                              disabled={isLoading}/>
                {gameId !== 0 && (
                    <>
                        <div className='flex flex-row flex-wrap justify-content-start gap-4' style={{marginTop: '1rem'}}>
                            <div className='flex flex-column align-items-stretch gap-2' style={{minWidth: '350px'}}>
                                <TrackSelector gameId={gameId}
                                               gameSupportsLayouts={gameSupportsLayouts}
                                               trackId={trackId}
                                               trackLayoutId={trackLayoutId}
                                               isInvalid={validationErrors.includes('track') || validationErrors.includes('trackLayout')}
                                               disabled={isLoading}
                                               onSelectedTrackChanged={setTrackId}
                                               onSelectedTrackLayoutChanged={setTrackLayoutId}
                                               trackValidationMessage={__('You must select a track for this event.', 'sim-league-toolkit')}
                                               trackLayoutValidationMessage={__('The game supports track layouts, you must select a track layout.', 'sim-league-toolkit')}/>
                                <label htmlFor='event-name'>{__('Name', 'sim-league-toolkit')}</label>
                                <InputText id='event-name' value={name}
                                           onChange={(e) => setName(e.target.value)}
                                           placeholder={__('Enter Name', 'sim-league-toolkit')}/>
                                <ValidationError
                                    message={__('A name with at least 5 characters is required', 'sim-league-toolkit')}
                                    show={validationErrors.includes('name')}/>
                                <label htmlFor='event-description'>{__('Description', 'sim-league-toolkit')}</label>
                                <InputTextarea id='event-description' value={description}
                                               onChange={(e) => setDescription(e.target.value)}
                                               placeholder={__('Enter a Description', 'sim-league-toolkit')}
                                               rows={5} cols={40}/>
                                <ValidationError
                                    message={__('A brief description with at least 15 characters is required.', 'sim-league-toolkit')}
                                    show={validationErrors.includes('description')}/>
                                <label htmlFor='event-date'>{__('Event Date', 'sim-league-toolkit')}</label>
                                <Calendar id='event-date' value={eventDate} onChange={(e) => setEventDate(e.value)}
                                          readOnlyInput dateFormat='D, M d yy'/>
                                <label htmlFor='event-start-time'>{__('Start Time', 'sim-league-toolkit')}</label>
                                <Calendar id='event-start-time' value={startTimeDate}
                                          onChange={(e) => setStartTimeDate(e.value as Date | null)}
                                          timeOnly hourFormat='24'
                                          placeholder={__('Select start time', 'sim-league-toolkit')}/>
                            </div>
                            <div className='flex flex-column align-items-stretch gap-2' style={{minWidth: '350px'}}>
                                <ScoringSetSelector scoringSetId={scoringSetId}
                                                    isInvalid={validationErrors.includes('scoringSet')}
                                                    validationMessage={__('You must select the scoring set this event will use.', 'sim-league-toolkit')}
                                                    onSelectedItemChanged={setScoringSetId}
                                                    disabled={isLoading}/>
                                <RuleSetSelector ruleSetId={ruleSetId}
                                                 onSelectedItemChanged={setRuleSetId}
                                                 disabled={isLoading}/>
                                <label htmlFor='max-entrants'>{__('Max Entrants', 'sim-league-toolkit')}</label>
                                <InputNumber id='max-entrants' value={maxEntrants}
                                             onChange={(e) => setMaxEntrants(e.value ?? 0)}
                                             min={0}/>
                                <div className='flex flex-row justify-content-between'>
                                    <label htmlFor='is-active'>{__('Active', 'sim-league-toolkit')}</label>
                                    <Checkbox id='is-active' checked={isActive}
                                              onChange={(e) => setIsActive(e.checked)}
                                              style={{marginTop: '.75rem'}}/>
                                </div>
                            </div>
                        </div>
                        <SaveSubmitButton disabled={isLoading} name='submitForm'/>
                        <CancelButton onCancel={onCancelled} disabled={isLoading}/>
                    </>
                )}
            </form>
        </>
    );
};
