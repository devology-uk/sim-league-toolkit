import {__} from '@wordpress/i18n';
import {useEffect, useState} from '@wordpress/element';
import apiFetch from '@wordpress/api-fetch';

import {Dialog} from 'primereact/dialog';
import {InputText} from 'primereact/inputtext';
import {InputTextarea} from 'primereact/inputtextarea';

import {BusySpinner} from '../shared/BusySpinner';
import {CancelButton} from '../shared/CancelButton';
import {GameSelector} from '../games/GameSelector';
import {SaveSubmitButton} from '../shared/SaveSubmitButton';
import {ValidationError} from '../shared/ValidationError';
import {BusyIndicator} from "../shared/BusyIndicator";

export const ChampionshipEditor = ({show, onSaved, onCancelled, championshipId = 0}) => {
    const [description, setDescription] = useState('');
    const [gameId, setGameId] = useState(0);
    const [gameName, setGameName] = useState('');
    const [isBusy, setIsBusy] = useState(false);
    const [name, setName] = useState('');
    const [validationErrors, setValidationErrors] = useState([]);

    useEffect(() => {
        if (championshipId === 0) {
            return;
        }

        apiFetch({
            path: `/sltk/v1/championship/${championshipId}`,
            method: 'GET',
        }).then((r) => {
            // setDescription(r.description);
            // setGameId(r.gameId);
            // setGameName(r.game);
            // setName(r.name);
            setIsBusy(false);
        });
    }, [championshipId]);

    const resetForm = () => {
        setDescription('');
        setGameId(0);
        setName('');
    }
    
    const onSave = (evt) => {
        evt.preventDefault();

        if (!validate()) {
            return;
        }

        setIsBusy(true);
        const entity = {
            description: description,
            gameId: gameId,
            name: name,
        };

        if (championshipId && championshipId > 0) {
            // entity.id = championshipId;
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
        setGameId(gameId);
    }

    const validate = () => {
        const errors = [];

        if (gameId < 1) {
            errors.push('game');
        }

        if (!name || name.length < 5) {
            errors.push('name');
        }

        if (!description || description.length < 15) {
            errors.push('description');
        }
        setValidationErrors(errors);
        return errors.length === 0;
    }

    return (
        <>
            {show && (
                <Dialog visible={show} onHide={onCancelled} header={__('Championship', 'sim-league-toolkit')}>
                    <BusyIndicator isBusy={isBusy} />
                    <form onSubmit={onSave} noValidate>
                        <div className='flex flex-row  align-items-stretch gap-4'>
                            <div className='flex flex-column align-items-stretch gap-2' style={{minWidth: '300px'}}>
                                {championshipId < 1 &&
                                    <GameSelector gameId={gameId}
                                                  disabled={championshipId !== 0}
                                                  isInvalid={validationErrors.includes('game')}
                                                  validationMessage={__('You must select the game that this championship will use.')}
                                                  onSelectedItemChanged={onSelectedGameChanged}/>
                                }
                                {championshipId >= 1 &&
                                    <>
                                        <label htmlFor='championship-name'>{__('Game', 'sim-league-toolkit')}</label>
                                        <span>{gameName}</span>
                                    </>
                                }
                                {gameId !== 0 &&
                                    <>
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

                                    </>

                                }

                            </div>
                        </div>
                        <SaveSubmitButton disabled={isBusy} name='submitForm'/>
                        <CancelButton onCancel={onCancelled} disabled={isBusy}/>
                    </form>
                </Dialog>
            )}
        </>
    )
}