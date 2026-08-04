import {__} from '@wordpress/i18n';
import {registerBlockType, type BlockEditProps} from '@wordpress/blocks';
import {InspectorControls, useBlockProps} from '@wordpress/block-editor';
import {PanelBody, SelectControl, Spinner} from '@wordpress/components';
import {useEffect, useState} from '@wordpress/element';
import apiFetch from '@wordpress/api-fetch';
import ServerSideRender from '@wordpress/server-side-render';

import metadata from './block.json';
import './style.scss';

interface ChampionshipOption {
    id: number;
    name: string;
}

function Edit({attributes, setAttributes}: BlockEditProps<Record<string, unknown>>) {
    const blockProps = useBlockProps();
    const [options, setOptions] = useState<ChampionshipOption[]>([]);
    const [isLoading, setIsLoading] = useState(true);
    const championshipId = Number(attributes.championshipId ?? 0);

    useEffect(() => {
        apiFetch<ChampionshipOption[]>({path: '/sltk/v1/championships'})
            .then(setOptions)
            .finally(() => setIsLoading(false));
    }, []);

    const selectOptions = [
        {label: __('Select a championship…', 'sim-league-toolkit'), value: '0'},
        ...options.map((option) => ({label: option.name, value: String(option.id)})),
    ];

    return (
        <div {...blockProps}>
            <InspectorControls>
                <PanelBody title={__('Championship', 'sim-league-toolkit')}>
                    {isLoading ? (
                        <Spinner />
                    ) : (
                        <SelectControl
                            label={__('Championship', 'sim-league-toolkit')}
                            value={String(championshipId)}
                            options={selectOptions}
                            onChange={(value: string) => setAttributes({championshipId: Number(value)})}
                        />
                    )}
                </PanelBody>
            </InspectorControls>
            {championshipId > 0 ? (
                <ServerSideRender block="sltk/championship-tile" attributes={attributes} />
            ) : (
                <p>{__('Select a championship in the block settings.', 'sim-league-toolkit')}</p>
            )}
        </div>
    );
}

registerBlockType(metadata.name, {
    ...metadata,
    edit: Edit,
    save: () => null,
});
