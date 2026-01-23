import {__} from '@wordpress/i18n';
import apiFetch from '@wordpress/api-fetch';
import {useEffect, useState} from '@wordpress/element';
import {FormEvent} from 'react';

import {Checkbox} from 'primereact/checkbox';
import {Dialog} from 'primereact/dialog';
import {InputText} from 'primereact/inputtext';

import {BusyIndicator} from '../shared/BusyIndicator';
import {CancelButton} from '../shared/CancelButton';
import {GameSelector} from '../games/GameSelector';
import {HttpMethod} from '../../enums/HttpMethod';
import {PlatformSelector} from '../games/PlatformSelector';
import {SaveSubmitButton} from '../shared/SaveSubmitButton';
import {Server} from '../../types/Server';
import {serverGetRoute, serverPostRoute} from '../../api/routes/serverApiRoutes';
import {ServerSettingList} from './ServerSettingList';
import {ValidationError} from '../shared/ValidationError';

interface ServerEditorProps {
    show: boolean;
    onSaved: () => void;
    onCancelled: () => void;
    serverId?: number;
}

export const ServerEditor = ({show, onSaved, onCancelled, serverId = 0}: ServerEditorProps) => {
    const [gameId, setGameId] = useState(0);
    const [gameKey, setGameKey] = useState('');
    const [gameName, setGameName] = useState('');
    const [isBusy, setIsBusy] = useState(false);
    const [isHostedServer, setIsHostedServer] = useState(false);
    const [name, setName] = useState('');
    const [platformId, setPlatformId] = useState(0);
    const [validationErrors, setValidationErrors] = useState<string[]>([]);

    useEffect(() => {
        if (serverId === 0) {
            return;
        }

        apiFetch({
                     path: serverGetRoute(serverId),
                     method: HttpMethod.GET,
                 }).then((r: Server) => {
            setGameId(r.gameId);
            setGameKey(r.gameKey);
            setGameName(r.game);
            setIsHostedServer(r.isHostedServer);
            setName(r.name);
            setPlatformId(r.platformId);

            setIsBusy(false);
        });
    }, [serverId]);

    const resetForm = () => {
        setGameId(0);
        setIsHostedServer(false);
        setName('');
        setPlatformId(0);
    };

    const onSave = (e: FormEvent<HTMLFormElement>) => {
        e.preventDefault();

        if (!validate()) {
            return;
        }

        setIsBusy(true);
        const entity: Server = {
            gameId: gameId,
            isHostedServer: isHostedServer,
            name: name,
            platformId: platformId,
        };

        if (serverId && serverId > 0) {
            entity.id = serverId;
        }

        apiFetch({
                     path: serverPostRoute(),
                     method: HttpMethod.POST,
                     data: entity,
                 }).then(() => {
            onSaved();

            resetForm();
            setIsBusy(false);
        });
    };

    const onSelectedGameChanged = (gameId: number) => {
        setGameId(gameId);
    };

    const onSelectedPlatformChanged = (platformId: number) => {
        setPlatformId(platformId);
    };

    const validate = () => {
        const errors = [];

        if (gameId < 1) {
            errors.push('game');
        }

        if (!name || name.length < 5) {
            errors.push('name');
        }

        if (platformId < 1) {
            errors.push('platform');
        }

        setValidationErrors(errors);
        return errors.length === 0;
    };

    return (
        <>
            {show && (
                <Dialog visible={show} onHide={onCancelled} header={__('Server', 'sim-league-toolkit')}>
                    <BusyIndicator isBusy={isBusy}/>
                    <div className='flex flex-row  align-items-stretch gap-4' style={{minWidth: '750px'}}>
                        <form onSubmit={onSave} noValidate>
                            <div className='flex flex-column align-items-stretch gap-2'>
                                {serverId < 1 &&
                                    <GameSelector gameId={gameId}
                                                  isInvalid={validationErrors.includes('game')}
                                                  validationMessage={__(
                                                      'You must select the game that this server will be used with.')}
                                                  onSelectedItemChanged={(g) => onSelectedGameChanged(g.id)}/>
                                }
                                {serverId >= 1 &&
                                    <>
                                        <label htmlFor='server-name'>{__('Game', 'sim-league-toolkit')}</label>
                                        <span>{gameName}</span>
                                    </>
                                }
                                {gameId !== 0 &&
                                    <>
                                        <PlatformSelector gameId={gameId}
                                                          platformId={platformId}
                                                          isInvalid={validationErrors.includes('platform')}
                                                          validationMessage={__(
                                                              'You must select the platform that this server will be used with.')}
                                                          onSelectedItemChanged={(p) => onSelectedPlatformChanged(p.id)}/>

                                        <label htmlFor='server-name'>{__('Name', 'sim-league-toolkit')}</label>
                                        <InputText id='server-name' value={name}
                                                   onChange={(e) => setName(e.target.value)}
                                                   placeholder={__('Enter Name', 'sim-league-toolkit')}/>
                                        <ValidationError
                                            message={__('A name with at least 5 characters is required',
                                                        'sim-league-toolkit')}
                                            show={validationErrors.includes('name')}/>

                                        <div className='flex flex-row justify-content-between'>
                                            <label
                                                htmlFor='is-hosted-server'>{__('Is Hosted',
                                                                               'sim-league-toolkit')}</label>
                                            <Checkbox id='is-hosted-server' checked={isHostedServer}
                                                      onChange={(e) => setIsHostedServer(e.checked)}/>
                                        </div>
                                    </>
                                }
                            </div>
                            <SaveSubmitButton disabled={isBusy} name='submitServer'/>
                            <CancelButton onCancel={onCancelled} disabled={isBusy}/>
                        </form>
                        {serverId > 0 && (<ServerSettingList serverId={serverId} gameKey={gameKey}/>)}
                    </div>
                </Dialog>
            )}
        </>
    );
};