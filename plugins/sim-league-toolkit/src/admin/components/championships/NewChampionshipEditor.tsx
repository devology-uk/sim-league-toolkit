import {__} from '@wordpress/i18n';
import {useEffect, useState} from '@wordpress/element';
import apiFetch from '@wordpress/api-fetch';

import {Calendar} from 'primereact/calendar';
import {Checkbox} from 'primereact/checkbox';
import {InputNumber} from 'primereact/inputnumber';
import {InputText} from 'primereact/inputtext';
import {InputTextarea} from 'primereact/inputtextarea';

import {BusySpinner} from '../shared/BusySpinner';
import {CancelButton} from '../shared/CancelButton';
import {ChampionshipTypeSelector} from './ChampionshipTypeSelector';
import {GameSelector} from '../games/GameSelector';
import {PlatformSelector} from '../shared/PlatformSelector';
import {RuleSetSelector} from '../rules/RuleSetSelector';
import {SaveSubmitButton} from '../shared/SaveSubmitButton';
import {ValidationError} from '../shared/ValidationError';
import {ScoringSetSelector} from '../scoringSets/ScoringSetSelector';
import {TrackSelector} from '../shared/TrackSelector';

const minDate = new Date();

export const NewChampionshipEditor = ({onSaved, onCancelled}) => {
    const [allowEntryChange, setAllowEntryChange] = useState(true);
    const [championshipType, setChampionshipType] = useState('std');
    const [description, setDescription] = useState('');
    const [entryChangeLimit, setEntryChangeLimit] = useState(1);
    const [gameId, setGameId] = useState(0);
    const [gameSupportsLayouts, setGameSupportsLayouts] = useState(false);
    const [isBusy, setIsBusy] = useState(false);
    const [name, setName] = useState('');
    const [platformId, setPlatformId] = useState(0);
    const [resultsToDiscard, setResultsToDiscard] = useState(0);
    const [ruleSetId, setRuleSetId] = useState(0);
    const [scoringSetId, setScoringSetId] = useState(0);
    const [startDate, setStartDate] = useState(minDate);
    const [trackMasterTrackId, setTrackMasterTrackId] = useState(0);
    const [trackMasterTrackLayoutId, setTrackMasterTrackLayoutId] = useState(0);
    const [validationErrors, setValidationErrors] = useState([]);

    useEffect(() => {
        apiFetch({
            path: `/sltk/v1/game/${gameId}`,
            method: 'GET'
        }).then((r) => {
            setGameSupportsLayouts(r.supportsLayouts);
        });
    }, [gameId]);

    const resetForm = () => {
        setGameId(0);
        setPlatformId(0);
        setAllowEntryChange(false);
        setChampionshipType('std');
        setEntryChangeLimit(0);
        setDescription('');
        setName('');
        setResultsToDiscard(0);
        setRuleSetId(0);
        setScoringSetId(0);
        setStartDate(minDate);
        setTrackMasterTrackId(0);
        setTrackMasterTrackLayoutId(0);
        setValidationErrors([]);
    }

    const onSave = (evt) => {
        evt.preventDefault();

        if (!validate()) {
            return;
        }

        setIsBusy(true);
        const entity = {
            allowEntryChange: allowEntryChange,
            bannerImageUrl: '',
            description: description,
            entryChangeLimit: entryChangeLimit,
            gameId: gameId,
            isTrackMasterChampionship: championshipType !== 'std',
            name: name,
            platformId: platformId,
            resultsToDiscard: resultsToDiscard,
            ruleSetId: ruleSetId,
            scoringSetId: scoringSetId,
            startDate: startDate
        };

        if(entity.isTrackMasterChampionship) {
            entity.trackMasterTrackId = trackMasterTrackId;

            if(gameSupportsLayouts) {
                entity.trackMasterTrackLayoutId = trackMasterTrackLayoutId;
            }
        }

        apiFetch({
            path: '/sltk/v1/championship',
            method: 'POST',
            data: entity,
        }).then(() => {
            onSaved();
            resetForm();
            setIsBusy(false);
        });
    }

    const onSelectedGameChanged = (gameId) => {
        setTrackMasterTrackId(0);
        setTrackMasterTrackLayoutId(0);
        setGameId(gameId);
    }

    const validate = () => {
        const errors = [];

        if (gameId < 1) {
            errors.push('game');
        }

        if (platformId < 1) {
            errors.push('platform');
        }

        if (!name || name.length < 5) {
            errors.push('name');
        }

        if (!description || description.length < 15) {
            errors.push('description');
        }

        if(scoringSetId < 1){
            errors.push('scoringSet');
        }

        if(championshipType !== 'std') {
            if(trackMasterTrackId < 1) {
                errors.push('trackMasterTrack');
            }

            if(gameSupportsLayouts && trackMasterTrackLayoutId < 1) {
                errors.push('trackMasterTrackLayout');
            }
        }

        setValidationErrors(errors);
        return errors.length === 0;
    }

    return (
        <>
            <h3>{__('New Championship', 'sim-league-toolkit')}</h3>
            <form onSubmit={onSave} noValidate>
                <GameSelector gameId={gameId}
                              isInvalid={validationErrors.includes('game')}
                              validationMessage={__('You must select the game that this championship will use.')}
                              onSelectedItemChanged={onSelectedGameChanged}/>
                {gameId !== 0 &&
                    <>
                        <div className='flex flex-row flex-wrap justify-content-between gap-4'>
                            <div className='flex flex-column align-items-stretch gap-2' style={{minWidth: '350px'}}>
                                <PlatformSelector gameId={gameId}
                                                  isInvalid={validationErrors.includes('platform')}
                                                  validationMessage={__('You must select the platform the championship will use.', 'sim-league-toolkit')}
                                                  onSelectedItemChanged={(id) => setPlatformId(id)}
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
                                               rows={7} cols={45}/>
                                <ValidationError
                                    message={__('A brief description of the championship with at least 15 characters is required.', 'sim-league-toolkit')}
                                    show={validationErrors.includes('description')}/>

                                <label
                                    htmlFor='championship-start-date'>{__('Start Date', 'sim-league-toolkit')}</label>
                                <Calendar value={startDate} onChange={(e) => setStartDate(e.value)}
                                          minDate={minDate} readOnlyInput dateFormat='D, M d yy'/>

                                <ChampionshipTypeSelector championshipTye={championshipType}
                                                          onSelectedItemChanged={(t) => setChampionshipType(t)}
                                                          disabled={isBusy}/>
                            </div>
                            <div className='flex flex-column align-items-stretch gap-2' style={{minWidth: '350px'}}>
                                <RuleSetSelector ruleSetId={ruleSetId}
                                                 onSelectedItemChanged={(rs) => setRuleSetId(rs)}
                                                 disabled={isBusy}/>
                                <ScoringSetSelector scoringSetId={scoringSetId}
                                                    isInvalid={validationErrors.includes('scoringSet')}
                                                    validationMessage={__('You must select the scoring set the championship will use.')}
                                                    onSelectedItemChanged={(s) => setScoringSetId(s)} />
                                <label
                                    htmlFor='results-to-discard'>{__('Worst Results to Discard', 'sim-league-toolkit')}</label>
                                <InputNumber id='results-to-discard' value={resultsToDiscard}
                                             onChange={(e) => setResultsToDiscard(e.value)}
                                             min={0}/>
                                <div className='flex flex-row justify-content-between'>
                                    <label
                                        htmlFor='allow-entry-change'>{__('Allow Entry Changes', 'sim-league-toolkit')}</label>
                                    <Checkbox id='allow-entry-change' checked={allowEntryChange}
                                              onChange={(e) => setAllowEntryChange(e.checked)} style={{marginTop: '.75rem'}}/>
                                </div>
                                {allowEntryChange && <>
                                    <label
                                        htmlFor='entry-change-limit'>{__('Entry Change Limit', 'sim-league-toolkit')}</label>
                                    <InputNumber id='entry-change-limit' value={entryChangeLimit}
                                                 onChange={(e) => setEntryChangeLimit(e.value)}
                                                 min={1}/>
                                </>}
                                {championshipType !== 'std' &&
                                    <TrackSelector gameId={gameId}
                                                   gameSupportsLayouts={gameSupportsLayouts}
                                                   trackId={trackMasterTrackId}
                                                   trackLayoutId={trackMasterTrackLayoutId}
                                                   isInvalid={validationErrors.includes('trackMasterTrack') || validationErrors.includes('trackMasterTrackLayout')}
                                                   disabled={isBusy}
                                                   onSelectedTrackChanged={(t) => setTrackMasterTrackId(t)}
                                                   onSelectedTrackLayoutChanged={(l) => setTrackMasterTrackLayoutId(l)}
                                                   trackValidationMessage={__('When the championship type is track master you must select the track that will be used for all events.', 'sim-league-toolkit')}
                                                   trackLayoutValidationMessage={__('The game supports track layouts, you must select a track layout that will be used for all events.', 'sim-league-toolkit' )}
                                    /> }
                            </div>
                        </div>
                        <SaveSubmitButton disabled={isBusy} name='submitForm'/>
                        <CancelButton onCancel={onCancelled} disabled={isBusy}/>
                    </>
                }
            </form>
            <BusySpinner isBusy={isBusy}/>
        </>
    )
}