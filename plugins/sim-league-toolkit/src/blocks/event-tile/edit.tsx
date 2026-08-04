import {__} from '@wordpress/i18n';
import {registerBlockType, type BlockEditProps} from '@wordpress/blocks';
import {InspectorControls, useBlockProps} from '@wordpress/block-editor';
import {PanelBody, SelectControl, Spinner} from '@wordpress/components';
import {useEffect, useState} from '@wordpress/element';
import apiFetch from '@wordpress/api-fetch';
import ServerSideRender from '@wordpress/server-side-render';

import metadata from './block.json';
import './style.scss';

interface EventOption {
    id: number;
    name: string;
}

function Edit({attributes, setAttributes}: BlockEditProps<Record<string, unknown>>) {
    const blockProps = useBlockProps();
    const [options, setOptions] = useState<EventOption[]>([]);
    const [isLoading, setIsLoading] = useState(true);
    const eventId = Number(attributes.eventId ?? 0);

    useEffect(() => {
        apiFetch<EventOption[]>({path: '/sltk/v1/standalone-events'})
            .then(setOptions)
            .finally(() => setIsLoading(false));
    }, []);

    const selectOptions = [
        {label: __('Select an event…', 'sim-league-toolkit'), value: '0'},
        ...options.map((option) => ({label: option.name, value: String(option.id)})),
    ];

    return (
        <div {...blockProps}>
            <InspectorControls>
                <PanelBody title={__('Event', 'sim-league-toolkit')}>
                    {isLoading ? (
                        <Spinner />
                    ) : (
                        <SelectControl
                            label={__('Event', 'sim-league-toolkit')}
                            value={String(eventId)}
                            options={selectOptions}
                            onChange={(value: string) => setAttributes({eventId: Number(value)})}
                        />
                    )}
                </PanelBody>
            </InspectorControls>
            {eventId > 0 ? (
                <ServerSideRender block="sltk/event-tile" attributes={attributes} />
            ) : (
                <p>{__('Select an event in the block settings.', 'sim-league-toolkit')}</p>
            )}
        </div>
    );
}

registerBlockType(metadata.name, {
    ...metadata,
    edit: Edit,
    save: () => null,
});
