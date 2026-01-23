import {useEffect, useState} from '@wordpress/element';
import apiFetch from '@wordpress/api-fetch';
import {__} from '@wordpress/i18n';

import {Dropdown, DropdownChangeEvent} from 'primereact/dropdown';

import {HttpMethod} from '../shared/HttpMethod';
import {ListItem} from "../../types/ListItem";
import {Track} from "../../types/Track";
import {tracksGetRoute, trackGetRoute} from './gameApiRoutes';
import {TrackLayout} from "../../types/TrackLayout";
import {ValidationError} from '../shared/ValidationError';

interface TrackSelectorProps {
    onSelectedTrackChanged: (itemId: number) => void;
    onSelectedTrackLayoutChanged: (itemId: number) => void;
    gameId: number;
    gameSupportsLayouts: boolean;
    trackId?: number;
    trackLayoutId?: number;
    disabled?: boolean;
    isInvalid?: boolean;
    trackValidationMessage?: string
    trackLayoutValidationMessage?: string
}

export const TrackSelector = ({
                                  onSelectedTrackChanged,
                                  onSelectedTrackLayoutChanged,
                                  gameId,
                                  gameSupportsLayouts,
                                  trackId = 0,
                                  trackLayoutId = 0,
                                  disabled = false,
                                  isInvalid = false,
                                  trackValidationMessage = '',
                                  trackLayoutValidationMessage = ''
                              }: TrackSelectorProps) => {
    const [selectedTrackId, setSelectedTrackId] = useState(trackId);
    const [selectedTrackLayoutId, setSelectedTrackLayoutId] = useState(trackLayoutId);
    const [tracks, setTracks] = useState<Track[]>([]);
    const [trackLayouts, setTrackLayouts] = useState<TrackLayout[]>([]);

    useEffect(() => {
        apiFetch({
            path: tracksGetRoute(gameId),
            method: HttpMethod.GET,
        }).then((r: Track[]) => {
            setTracks(r);
        });
    }, [gameId]);

    useEffect(() => {
        setSelectedTrackId(trackId);

        if(!gameSupportsLayouts) {
            setTrackLayouts([]);
            return;
        }

        apiFetch({
            path: trackGetRoute(trackId),
            method: HttpMethod.GET,
        }).then((r: TrackLayout[]) => {
            setTrackLayouts(r);
        });
    }, [trackId, gameSupportsLayouts]);

    useEffect(() => {
        setSelectedTrackLayoutId(trackLayoutId);
    }, [trackLayoutId]);

    const onSelectTrack = (e: DropdownChangeEvent) => {
        setSelectedTrackId(e.target.value);
        onSelectedTrackChanged(e.target.value);
    }

    const onSelectTrackLayout = (e:DropdownChangeEvent) => {
        setSelectedTrackLayoutId(e.target.value);
        onSelectedTrackLayoutChanged(e.target.value);
    }

    const trackListItems: ListItem[] = ([{value: 0, label: __('Please select...', 'sim-league-toolkit')}] as ListItem[])
        .concat(tracks.map(i => ({
            value: i.id,
            label: i.shortName
        })));

    const trackLayoutItems: ListItem[] = ([{
        value: 0,
        label: __('Please select...', 'sim-league-toolkit')
    }] as ListItem[]).concat(trackLayouts.map(i => ({
        value: i.id,
        label: i.name
    })));

    return (
        <div className='flex flex-column align-items-stretch gap-2' style={{maxWidth: '350px'}}>
            <label htmlFor='track-selector'>{__('Track', 'sim-league-toolkit')}</label>
            <Dropdown id='track-selector' value={selectedTrackId} options={trackListItems} onChange={onSelectTrack}
                      optionLabel='label'
                      optionValue='value' disabled={disabled}/>
            <ValidationError
                message={trackValidationMessage}
                show={isInvalid && trackId === 0}/>
            {gameSupportsLayouts && trackId !== 0 &&
                <>
                    <label htmlFor='track-layout-selector'>{__('Track Layout', 'sim-league-toolkit')}</label>
                    <Dropdown id='track-layout-selector' value={selectedTrackLayoutId} options={trackLayoutItems}
                              onChange={onSelectTrackLayout}
                              optionLabel='label'
                              optionValue='value' disabled={disabled}/>
                    <ValidationError
                        message={trackLayoutValidationMessage}
                        show={isInvalid && trackLayoutId === 0}/>
                </>
            }
        </div>
    )
}