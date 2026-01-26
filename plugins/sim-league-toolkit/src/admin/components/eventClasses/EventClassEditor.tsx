import {__} from '@wordpress/i18n';
import {useEffect, useState} from '@wordpress/element';
import {FormEvent} from 'react';

import {Checkbox} from 'primereact/checkbox';
import {Dialog} from 'primereact/dialog';
import {InputText} from 'primereact/inputtext';

import {BusySpinner} from '../shared/BusySpinner';
import {CancelButton} from '../shared/CancelButton';
import {CAR_CLASS_SELECTOR_DEFAULT_VALUE, CarClassSelector} from '../games/CarClassSelector';
import {CarSelector} from '../games/CarSelector';
import {DriverCategorySelector} from './DriverCategorySelector';
import {EventClassFormData} from '../../types/EventClassFormData';
import {GameSelector} from '../games/GameSelector';
import {SaveSubmitButton} from '../shared/SaveSubmitButton';
import {useEventClasses} from '../../hooks/useEventClasses';
import {ValidationError} from '../shared/ValidationError';

interface EventClassEditorProps {
    show: boolean;
    onSaved: () => void;
    onCancelled: () => void;
    eventClassId?: number;
}

export const EventClassEditor = ({show, onSaved, onCancelled, eventClassId = 0}: EventClassEditorProps) => {
    const {createEventClass, deleteEventClass, findEventClass, isLoading, updateEventClass} = useEventClasses();

    const [carClass, setCarClass] = useState(CAR_CLASS_SELECTOR_DEFAULT_VALUE);
    const [driverCategoryId, setDriverCategoryId] = useState(0);
    const [gameId, setGameId] = useState(0);
    const [gameName, setGameName] = useState('');
    const [isSingleCarClass, setIsSingleCarClass] = useState(false);
    const [name, setName] = useState('');
    const [singleCarId, setSingleCarId] = useState(0);
    const [validationErrors, setValidationErrors] = useState([]);

    useEffect(() => {
        if (eventClassId === 0) {
            return;
        }

        const eventClass = findEventClass(eventClassId);
        setCarClass(eventClass.carClass);
        setDriverCategoryId(driverCategoryId);
        setGameId(eventClass.gameId);
        setGameName(eventClass.game);
        setIsSingleCarClass(eventClass.isSingleCarClass);
        setSingleCarId(eventClass.singleCarId ?? 0);
    }, [eventClassId]);

    const resetForm = () => {
        setCarClass(CAR_CLASS_SELECTOR_DEFAULT_VALUE);
        setDriverCategoryId(0);
        setGameId(0);
        setIsSingleCarClass(false);
        setName('');
        setSingleCarId(0);
    };

    const onSave = async (e: FormEvent<HTMLFormElement>) => {
        e.preventDefault();

        if (!validate()) {
            return;
        }

        const formData: EventClassFormData = {
            carClass: carClass,
            driverCategoryId: driverCategoryId,
            gameId: gameId,
            isSingleCarClass: isSingleCarClass,
            name: name,
            singleCarId: isSingleCarClass ? singleCarId : null,
        };

        if (eventClassId === 0) {
            await createEventClass(formData);
        } else {
            await updateEventClass(eventClassId, formData);
        }

        resetForm();
    };

    const onSelectedGameChanged = (gameId) => {
        setGameId(gameId);
    };

    const validate = () => {
        const errors = [];

        if (gameId < 1) {
            errors.push('game');
        }

        if (!name || name.length < 5) {
            errors.push('name');
        }

        if (driverCategoryId < 1) {
            errors.push('driverCategoryId');
        }

        if (!carClass || carClass.length < 1 || carClass === CAR_CLASS_SELECTOR_DEFAULT_VALUE) {
            errors.push('carClass');
        }

        if (isSingleCarClass && singleCarId < 1) {
            errors.push('singleCarId');
        }
        setValidationErrors(errors);
        return errors.length === 0;
    };

    return (
        <>
            {show && (
                <Dialog visible={show} onHide={onCancelled} header={__('Event Class', 'sim-league-toolkit')}>
                    <BusySpinner isBusy={isLoading}/>
                    <form onSubmit={onSave} noValidate>
                        <div className='flex flex-row  align-items-stretch gap-4'>
                            <div className='flex flex-column align-items-stretch gap-2' style={{minWidth: '300px'}}>
                                {eventClassId < 1 &&
                                    <GameSelector gameId={gameId}
                                                  disabled={eventClassId !== 0}
                                                  isInvalid={validationErrors.includes('game')}
                                                  validationMessage={__(
                                                      'You must select the game that this class will be used with.')}
                                                  onSelectedItemChanged={onSelectedGameChanged}/>
                                }
                                {eventClassId >= 1 &&
                                    <>
                                        <label htmlFor='event-class-name'>{__('Game', 'sim-league-toolkit')}</label>
                                        <span>{gameName}</span>
                                    </>
                                }
                                {gameId !== 0 &&
                                    <>
                                        <label htmlFor='event-class-name'>{__('Name', 'sim-league-toolkit')}</label>
                                        <InputText id='event-class-name' value={name}
                                                   onChange={(e) => setName(e.target.value)}
                                                   placeholder={__('Enter Name', 'sim-league-toolkit')}/>
                                        <ValidationError
                                            message={__('A name with at least 5 characters is required',
                                                        'sim-league-toolkit')}
                                            show={validationErrors.includes('name')}/>

                                        <DriverCategorySelector driverCategoryId={driverCategoryId}
                                                                disabled={isLoading}
                                                                isInvalid={validationErrors.includes('driverCategoryId')}
                                                                onSelectedItemChanged={setDriverCategoryId}
                                                                validationMessage={__(
                                                                    'You must select a driver category.',
                                                                    'sim-league-toolkit')}/>
                                        <CarClassSelector gameId={gameId}
                                                          carClass={carClass}
                                                          disabled={isLoading}
                                                          isInvalid={validationErrors.includes('carClass')}
                                                          onSelectedItemChanged={setCarClass}
                                                          validationMessage={__('You must select a car class.',
                                                                                'sim-league-toolkit')}/>
                                        <div className='flex flex-row justify-content-between'>
                                            <label
                                                htmlFor='is-single-car-class'>{__('Is Fixed Car',
                                                                                  'sim-league-toolkit')}</label>
                                            <Checkbox id='is-single-car-class' checked={isSingleCarClass}
                                                      onChange={(e) => setIsSingleCarClass(e.checked)}/>
                                        </div>
                                        {isSingleCarClass && carClass !== CAR_CLASS_SELECTOR_DEFAULT_VALUE &&
                                            <CarSelector gameId={gameId}
                                                         carClass={carClass}
                                                         carId={singleCarId}
                                                         disabled={isLoading}
                                                         isInvalid={validationErrors.includes('singleCarId')}
                                                         onSelectedItemChanged={(c) => setSingleCarId(c.id)}
                                                         validationMessage={__(
                                                             'When Is Fixed Car is enabled you must select a car.',
                                                             'sim-league-toolkit')}/>}
                                    </>

                                }

                            </div>
                        </div>
                        <SaveSubmitButton disabled={isLoading} name='submitForm'/>
                        <CancelButton onCancel={onCancelled} disabled={isLoading}/>
                    </form>
                </Dialog>
            )}
        </>
    );
};