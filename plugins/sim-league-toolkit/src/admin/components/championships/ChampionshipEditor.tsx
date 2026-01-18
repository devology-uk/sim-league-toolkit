import {__} from '@wordpress/i18n';
import {useEffect, useState} from '@wordpress/element';
import apiFetch from '@wordpress/api-fetch';
import {FormEvent} from "react";

import {Accordion, AccordionTab} from "primereact/accordion";
import {Calendar} from "primereact/calendar";
import {Checkbox} from "primereact/checkbox";
import {InputText} from 'primereact/inputtext';
import {InputNumber} from "primereact/inputnumber";
import {InputTextarea} from 'primereact/inputtextarea';

import {BusyIndicator} from "../shared/BusyIndicator";
import {CancelButton} from '../shared/CancelButton';
import {Championship} from "./Championship";
import {ChampionshipClasses} from "./ChampionshipClasses";
import {ChampionshipEvents} from '../championshipEvents/ChampionshipEvents';
import {championshipGetRoute, championshipPostRoute} from './championshipApiRoutes';
import {ChampionshipTypes, translateChampionshipType} from "./ChampionshipTypes";
import {Game} from "../games/Game";
import {gameGetRoute} from '../games/gameApiRoutes';
import {HttpMethod} from '../shared/HttpMethod';
import {PlatformSelector} from "../games/PlatformSelector";
import {RuleSetSelector} from "../rules/RuleSetSelector";
import {SaveSubmitButton} from '../shared/SaveSubmitButton';
import {ScoringSetSelector} from "../scoringSets/ScoringSetSelector";
import {TrackSelector} from "../games/TrackSelector";
import {ValidationError} from '../shared/ValidationError';

interface ChampionshipEditorProps {
    onSaved: () => void;
    onCancelled: () => void;
    championshipId?: number;
}

const minDate = new Date();

export const ChampionshipEditor = ({onSaved, onCancelled, championshipId = 0}: ChampionshipEditorProps) => {
    const [activeTabIndex, setActiveTabIndex] = useState<number>(0);
    const [allowEntryChange, setAllowEntryChange] = useState(true);
    const [bannerImageUrl, setBannerImageUrl] = useState('');
    const [championshipType, setChampionshipType] = useState(ChampionshipTypes.Standard);
    const [description, setDescription] = useState('');
    const [entryChangeLimit, setEntryChangeLimit] = useState(1);
    const [gameId, setGameId] = useState(0);
    const [gameName, setGameName] = useState('');
    const [gameSupportsLayouts, setGameSupportsLayouts] = useState(false);
    const [isActive, setIsActive] = useState<boolean>(false);
    const [isBusy, setIsBusy] = useState(false);
    const [isTrackMasterChampionship, setIsTrackMasterChampionship] = useState(false);
    const [name, setName] = useState('');
    const [platformId, setPlatformId] = useState(0);
    const [resultsToDiscard, setResultsToDiscard] = useState(0);
    const [ruleSetId, setRuleSetId] = useState(0);
    const [scoringSetId, setScoringSetId] = useState(0);
    const [startDate, setStartDate] = useState(minDate);
    const [trackMasterTrackId, setTrackMasterTrackId] = useState(0);
    const [trackMasterTrackLayoutId, setTrackMasterTrackLayoutId] = useState(0);
    const [trophiesAwarded, setTrophiesAwarded] = useState(false);
    const [validationErrors, setValidationErrors] = useState<string[]>([]);

    useEffect(() => {
        if (championshipId === 0) {
            return;
        }

        apiFetch({
            path: championshipGetRoute(championshipId),
            method: HttpMethod.GET,
        }).then((r: Championship) => {
            setAllowEntryChange(r.allowEntryChange);
            setBannerImageUrl(r.bannerImageUrl);
            setDescription(r.description);
            setEntryChangeLimit(r.entryChangeLimit);
            setGameId(r.gameId);
            setIsActive(r.isActive);
            setIsTrackMasterChampionship(r.isTrackMasterChampionship);
            setName(r.name);
            setPlatformId(r.platformId);
            setResultsToDiscard(r.resultsToDiscard);
            setRuleSetId(r.ruleSetId);
            setScoringSetId(r.scoringSetId);
            setStartDate(new Date(r.startDate));
            setTrophiesAwarded(r.trophiesAwarded);

            if (r.isTrackMasterChampionship) {
                setChampionshipType(ChampionshipTypes.Trackmaster);
                setTrackMasterTrackId(r.trackMasterTrackId);
                if (r.trackMasterTrackLayoutId > 0) {
                    setTrackMasterTrackLayoutId(r.trackMasterTrackLayoutId);
                }
            } else {
                setChampionshipType(ChampionshipTypes.Standard);
            }

            setIsBusy(false);
        });
    }, [championshipId]);

    useEffect(() => {
        if (gameId < 1) {
            return;
        }

        apiFetch({
            path: gameGetRoute(gameId),
            method: 'GET'
        }).then((r: Game) => {
            setGameName(r.name);
            setGameSupportsLayouts(r.supportsLayouts);
        });
    }, [gameId]);

    const onSave = (e: FormEvent<HTMLFormElement>) => {
        e.preventDefault();

        if (!validate()) {
            return;
        }

        setIsBusy(true);
        const entity: Championship = {
            allowEntryChange: allowEntryChange,
            bannerImageUrl: bannerImageUrl,
            description: description,
            entryChangeLimit: entryChangeLimit,
            gameId: gameId,
            isActive: isActive,
            isTrackMasterChampionship: championshipType === ChampionshipTypes.Trackmaster,
            name: name,
            platformId: platformId,
            resultsToDiscard: resultsToDiscard,
            ruleSetId: ruleSetId,
            scoringSetId: scoringSetId,
            startDate: startDate,
            trophiesAwarded: trophiesAwarded
        };

        if (entity.isTrackMasterChampionship) {
            entity.trackMasterTrackId = trackMasterTrackId;

            if (gameSupportsLayouts) {
                entity.trackMasterTrackLayoutId = trackMasterTrackLayoutId;
            }
        }

        if (championshipId && championshipId > 0) {
            entity.id = championshipId;
        }

        apiFetch({
            path: championshipPostRoute(),
            method: HttpMethod.POST,
            data: entity,
        }).then(() => {
            onSaved();
            setIsBusy(false);
        });
    }

    const validate = () => {
        const errors = [];

        if (platformId < 1) {
            errors.push('platform');
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

        if (championshipType === ChampionshipTypes.Trackmaster) {
            if (trackMasterTrackId < 1) {
                errors.push('trackMasterTrack');
            }

            if (gameSupportsLayouts && trackMasterTrackLayoutId < 1) {
                errors.push('trackMasterTrackLayout');
            }
        }

        setValidationErrors(errors);
        return errors.length === 0;
    }

    return (
        <>
            <BusyIndicator isBusy={isBusy}/>
            <h3>{__('Championship', 'sim-league-toolkit')} - {name}</h3>
            <h4>{__('Game', 'sim-league-toolkit')} - {gameName}</h4>
            <h4>{__('Type', 'sim-league-toolkit')} - {translateChampionshipType(championshipType)}</h4>
            <Accordion activeIndex={activeTabIndex}>
                <AccordionTab header={__('General Settings', 'sim-league-toolkit')}>
                    <form onSubmit={onSave} noValidate>
                        <div className='flex flex-row flex-wrap justify-content-between gap-4'>
                            <div className='flex flex-column align-items-stretch gap-2' style={{minWidth: '350px'}}>
                                <PlatformSelector gameId={gameId}
                                                  isInvalid={validationErrors.includes('platform')}
                                                  validationMessage={__('You must select the platform the championship will use.', 'sim-league-toolkit')}
                                                  onSelectedItemChanged={(p) => setPlatformId(p.id)}
                                                  platformId={platformId}/>
                                <label htmlFor='championship-name'>{__('Name', 'sim-league-toolkit')}</label>
                                <InputText id='championship-name' value={name}
                                           onChange={(e) => setName(e.target.value)}
                                           placeholder={__('Enter Name', 'sim-league-toolkit')}/>
                                <ValidationError
                                    message={__('A name with at least 5 characters is required', 'sim-league-toolkit')}
                                    show={validationErrors.includes('name')}/>
                                <label
                                    htmlFor='championship-description'>{__('Description', 'sim-league-toolkit')}</label>
                                <InputTextarea id='championship-description' value={description}
                                               onChange={(e) => setDescription(e.target.value)}
                                               placeholder={__('Enter a Description', 'sim-league-toolkit')}
                                               rows={5} cols={40}/>
                                <ValidationError
                                    message={__('A brief description of the championship with at least 15 characters is required.', 'sim-league-toolkit')}
                                    show={validationErrors.includes('description')}/>

                                <label
                                    htmlFor='championship-start-date'>{__('Start Date', 'sim-league-toolkit')}</label>
                                <Calendar value={startDate} onChange={(e) => setStartDate(e.value)}
                                          minDate={minDate} readOnlyInput dateFormat='D, M d yy'/>

                            </div>
                            <div className='flex flex-column align-items-stretch gap-2' style={{minWidth: '350px'}}>
                                <RuleSetSelector ruleSetId={ruleSetId}
                                                 onSelectedItemChanged={(rs) => setRuleSetId(rs)}
                                                 disabled={isBusy}/>
                                <ScoringSetSelector scoringSetId={scoringSetId}
                                                    isInvalid={validationErrors.includes('scoringSet')}
                                                    validationMessage={__('You must select the scoring set the championship will use.')}
                                                    onSelectedItemChanged={setScoringSetId}/>
                                <label
                                    htmlFor='results-to-discard'>{__('Worst Results to Discard', 'sim-league-toolkit')}</label>
                                <InputNumber id='results-to-discard' value={resultsToDiscard}
                                             onChange={(e) => setResultsToDiscard(e.value)}
                                             min={0}/>
                                <div className='flex flex-row justify-content-between'>
                                    <label
                                        htmlFor='allow-entry-change'>{__('Allow Entry Changes', 'sim-league-toolkit')}</label>
                                    <Checkbox id='allow-entry-change' checked={allowEntryChange}
                                              onChange={(e) => setAllowEntryChange(e.checked)}
                                              style={{marginTop: '.75rem'}}/>
                                </div>
                                {allowEntryChange && <>
                                    <label
                                        htmlFor='entry-change-limit'>{__('Entry Change Limit', 'sim-league-toolkit')}</label>
                                    <InputNumber id='entry-change-limit' value={entryChangeLimit}
                                                 onChange={(e) => setEntryChangeLimit(e.value)}
                                                 min={1}/>
                                </>}
                                {isTrackMasterChampionship &&
                                    <TrackSelector gameId={gameId}
                                                   gameSupportsLayouts={gameSupportsLayouts}
                                                   trackId={trackMasterTrackId}
                                                   trackLayoutId={trackMasterTrackLayoutId}
                                                   isInvalid={validationErrors.includes('trackMasterTrack') || validationErrors.includes('trackMasterTrackLayout')}
                                                   disabled={isBusy}
                                                   onSelectedTrackChanged={setTrackMasterTrackId}
                                                   onSelectedTrackLayoutChanged={setTrackMasterTrackLayoutId}
                                                   trackValidationMessage={__('When the championship type is track master you must select the track that will be used for all events.', 'sim-league-toolkit')}
                                                   trackLayoutValidationMessage={__('The game supports track layouts, you must select a track layout that will be used for all events.', 'sim-league-toolkit')}
                                    />}
                            </div>
                        </div>
                        <SaveSubmitButton disabled={isBusy} name='submitForm'/>
                        <CancelButton onCancel={onCancelled} disabled={isBusy}/>
                    </form>
                </AccordionTab>
                <AccordionTab header={__('Classes', 'sim-league-toolkit')}>
                    <ChampionshipClasses championshipId={championshipId} gameId={gameId} />
                </AccordionTab>
                <AccordionTab header={__('Events', 'sim-league-toolkit')}>
                    <ChampionshipEvents championshipId={championshipId} gameId={gameId} />
                </AccordionTab>
                <AccordionTab header={__('Server', 'sim-league-toolkit')}>
                </AccordionTab>
                <AccordionTab header={__('Entrants', 'sim-league-toolkit')}>
                </AccordionTab>
                <AccordionTab header={__('Standings', 'sim-league-toolkit')}>
                </AccordionTab>
            </Accordion>
        </>
    )
}