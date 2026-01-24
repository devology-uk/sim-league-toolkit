import {__} from '@wordpress/i18n';
import apiFetch from '@wordpress/api-fetch';
import {useState, useEffect} from '@wordpress/element';
import {FormEvent} from 'react';

import {Accordion, AccordionTab} from 'primereact/accordion';
import {Calendar} from 'primereact/calendar';
import {Checkbox} from 'primereact/checkbox';
import {InputText} from 'primereact/inputtext';
import {InputTextarea} from 'primereact/inputtextarea';

import {BusyIndicator} from '../shared/BusyIndicator';
import {CancelButton} from '../shared/CancelButton';
import {ChampionshipEvent} from '../../types/ChampionshipEvent';
import {championshipEventRootEndpoint} from '../../api/endpoints/championshipEventsApiRoutes';
import {Dialog} from 'primereact/dialog';
import {Game} from '../../types/Game';
import {gameGetRoute} from '../../api/endpoints/gameApiRoutes';
import {HttpMethod} from '../../enums/HttpMethod';
import {RuleSetSelector} from '../rules/RuleSetSelector';
import {SaveSubmitButton} from '../shared/SaveSubmitButton';
import {TrackSelector} from '../games/TrackSelector';
import {ValidationError} from '../shared/ValidationError';

interface ChampionshipEventEditorProps {
    championshipEvent: ChampionshipEvent;
    gameId: number;
    onSaved: () => void;
    onCancelled: () => void;
}

const minDate = new Date();
minDate.setHours(0);
minDate.setMinutes(0);
minDate.setSeconds(0);
minDate.setMilliseconds(0);

export const ChampionshipEventEditor = ({
                                            championshipEvent,
                                            gameId,
                                            onSaved,
                                            onCancelled
                                        }: ChampionshipEventEditorProps) => {

    const [activeTabIndex, setActiveTabIndex] = useState<number | number[]>(0);
    const [description, setDescription] = useState(championshipEvent.description);
    const [gameSupportsLayouts, setGameSupportsLayouts] = useState(false);
    const [isActive, setIsActive] = useState(championshipEvent.isActive);
    const [isBusy, setIsBusy] = useState(false);
    const [name, setName] = useState(championshipEvent.name);
    const [ruleSetId, setRuleSetId] = useState(championshipEvent.ruleSetId);
    const [startDateTime, setStartDateTime] = useState(new Date(championshipEvent.startDateTime));
    const [trackId, setTrackId] = useState(championshipEvent.trackId);
    const [trackLayoutId, setTrackLayoutId] = useState(championshipEvent.trackLayoutId);
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
        championshipEvent.description = description;
        championshipEvent.isActive = isActive;
        championshipEvent.name = name;
        championshipEvent.ruleSetId = ruleSetId;
        championshipEvent.startDateTime = startDateTime.toISOString();
        championshipEvent.trackId = trackId;

        if (gameSupportsLayouts) {
            championshipEvent.trackLayoutId = trackLayoutId;
        }

        apiFetch({
                     path: championshipEventRootEndpoint(),
                     method: HttpMethod.POST,
                     data: championshipEvent,
                 }).then(() => {
            onSaved();
            setIsBusy(false);
        });
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
                <Accordion activeIndex={activeTabIndex} onTabChange={(e) => setActiveTabIndex(e.index)}>
                    <AccordionTab header={__('Details', 'sim-league-toolkit')}>
                        <form onSubmit={onSave} noValidate>
                            <div className='flex flex-row  align-items-stretch gap-4'>
                                <div className='flex flex-column align-items-stretch gap-2' style={{minWidth: '300px'}}>
                                    <label htmlFor='championship-event-name'>{__('Name', 'sim-league-toolkit')}</label>
                                    <InputText id='championship-event-name' value={name}
                                               onChange={(e) => setName(e.target.value)}
                                               placeholder={__('Enter Name', 'sim-league-toolkit')}/>
                                    <ValidationError
                                        message={__('A name with at least 5 characters is required',
                                                    'sim-league-toolkit')}
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
                                              minDate={minDate} readOnlyInput dateFormat='D, M d yy' showTime
                                              hourFormat='24'/>

                                    <TrackSelector onSelectedTrackChanged={setTrackId}
                                                   onSelectedTrackLayoutChanged={setTrackLayoutId} gameId={gameId}
                                                   gameSupportsLayouts={gameSupportsLayouts} trackId={trackId}
                                                   trackLayoutId={trackLayoutId} disabled={isBusy}
                                                   isInvalid={validationErrors.includes('track') || validationErrors.includes(
                                                       'trackLayout')}
                                                   trackValidationMessage={__(
                                                       'You must select the track that will be used' +
                                                       ' for the event.',
                                                       'sim-league-toolkit')}
                                                   trackLayoutValidationMessage={__(
                                                       'The game supports track layouts, you' +
                                                       ' must select a track layout that' +
                                                       ' will be used for the event.',
                                                       'sim-league-toolkit')}/>
                                    <RuleSetSelector ruleSetId={ruleSetId}
                                                     onSelectedItemChanged={setRuleSetId}
                                                     disabled={isBusy}/>
                                    <div className='flex flex-row justify-content-between'>
                                        <label
                                            htmlFor='is-active'>{__('Active', 'sim-league-toolkit')}</label>
                                        <Checkbox id='is-active' checked={isActive}
                                                  onChange={(e) => setIsActive(e.checked)}
                                                  style={{marginTop: '.75rem'}}/>
                                    </div>
                                </div>
                            </div>
                            <SaveSubmitButton disabled={isBusy} name='submitForm'/>
                            <CancelButton onCancel={onCancelled} disabled={isBusy}/>
                        </form>
                    </AccordionTab>
                    <AccordionTab header={__('Sessions', 'sim-league-toolkit')}>

                    </AccordionTab>
                </Accordion>

            </Dialog>

        </>
    );
};