import {useEffect, useState} from '@wordpress/element';
import apiFetch from '@wordpress/api-fetch';
import {__} from '@wordpress/i18n';

import {Dropdown} from 'primereact/dropdown';

import {ValidationError} from './ValidationError';

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
                              }) => {
    const [selectedTrack, setSelectedTrack] = useState(trackId);
    const [selectedTrackLayout, setSelectedTrackLayout] = useState(trackLayoutId);
    const [tracks, setTracks] = useState([]);
    const [trackLayouts, setTrackLayouts] = useState([]);

    useEffect(() => {
        apiFetch({
            path: `/sltk/v1/game/${gameId}/tracks`,
            method: 'GET'
        }).then((r) => {
            setTracks(r);
        });
    }, [gameId]);

    useEffect(() => {
        apiFetch({
            path: `/sltk/v1/game/tracks/${trackId}/layouts`,
            method: 'GET'
        }).then((r) => {
            setTrackLayouts(r);
        });
    }, [trackId]);

    const onSelectTrack = (evt) => {
        setSelectedTrack(evt.target.value);
        onSelectedTrackChanged(evt.target.value);
    }

    const onSelectTrackLayout = (evt) => {
        setSelectedTrackLayout(evt.target.value);
        onSelectedTrackLayoutChanged(evt.target.value);
    }

    const trackOptions = [{value: 0, label: __('Please select...', 'sim-league-toolkit')}].concat(tracks.map(i => ({
        value: i.id,
        label: i.shortName
    })));

    const trackLayoutOptions = [{
        value: 0,
        label: __('Please select...', 'sim-league-toolkit')
    }].concat(trackLayouts.map(i => ({
        value: i.id,
        label: i.name
    })));

    return (
        <div className='flex flex-column align-items-stretch gap-2' style={{maxWidth: '350px'}}>
            <label htmlFor='track-selector'>{__('Track', 'sim-league-toolkit')}</label>
            <Dropdown id='track-selector' value={selectedTrack} options={trackOptions} onChange={onSelectTrack}
                      optionLabel='label'
                      optionValue='value' disabled={disabled}/>
            <ValidationError
                message={trackValidationMessage}
                show={isInvalid && trackId === 0}/>
            {gameSupportsLayouts && trackId !== 0 &&
                <>
                    <label htmlFor='track-layout-selector'>{__('Track Layout', 'sim-league-toolkit')}</label>
                    <Dropdown id='track-layout-selector' value={selectedTrackLayout} options={trackLayoutOptions}
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