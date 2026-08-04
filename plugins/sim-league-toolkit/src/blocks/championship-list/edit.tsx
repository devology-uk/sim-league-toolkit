import {__} from '@wordpress/i18n';
import {registerBlockType, type BlockEditProps} from '@wordpress/blocks';
import {InspectorControls, useBlockProps} from '@wordpress/block-editor';
import {PanelBody, TextControl, ToggleControl} from '@wordpress/components';
import ServerSideRender from '@wordpress/server-side-render';

import metadata from './block.json';
import './style.scss';

function Edit({attributes, setAttributes}: BlockEditProps<Record<string, unknown>>) {
    const blockProps = useBlockProps();
    const showAll = attributes.showAll !== false;
    const hasStartLimit = attributes.hasStartLimit !== false;
    const startOffsetDays = Number(attributes.startOffsetDays ?? 0);
    const hasEndLimit = Boolean(attributes.hasEndLimit);
    const endOffsetDays = Number(attributes.endOffsetDays ?? 0);
    const includeInactive = Boolean(attributes.includeInactive);

    return (
        <div {...blockProps}>
            <InspectorControls>
                <PanelBody title={__('Filter', 'sim-league-toolkit')}>
                    <ToggleControl
                        label={__('Show all championships', 'sim-league-toolkit')}
                        checked={showAll}
                        onChange={(value) => setAttributes({showAll: value})}
                    />
                    {!showAll && (
                        <>
                            <ToggleControl
                                label={__('Limit start date', 'sim-league-toolkit')}
                                checked={hasStartLimit}
                                onChange={(value) => setAttributes({hasStartLimit: value})}
                            />
                            {hasStartLimit && (
                                <TextControl
                                    label={__('Start (days from today)', 'sim-league-toolkit')}
                                    help={__('0 = today. Negative values look back, e.g. -14 for two weeks ago.', 'sim-league-toolkit')}
                                    type="number"
                                    value={String(startOffsetDays)}
                                    onChange={(value) => setAttributes({startOffsetDays: Number(value) || 0})}
                                />
                            )}
                            <ToggleControl
                                label={__('Limit end date', 'sim-league-toolkit')}
                                checked={hasEndLimit}
                                onChange={(value) => setAttributes({hasEndLimit: value})}
                            />
                            {hasEndLimit && (
                                <TextControl
                                    label={__('End (days from today)', 'sim-league-toolkit')}
                                    help={__('0 = today. Negative values are in the past, e.g. -14 for two weeks ago.', 'sim-league-toolkit')}
                                    type="number"
                                    value={String(endOffsetDays)}
                                    onChange={(value) => setAttributes({endOffsetDays: Number(value) || 0})}
                                />
                            )}
                        </>
                    )}
                    <ToggleControl
                        label={__('Include inactive', 'sim-league-toolkit')}
                        checked={includeInactive}
                        onChange={(value) => setAttributes({includeInactive: value})}
                    />
                </PanelBody>
            </InspectorControls>
            <ServerSideRender block="sltk/championship-list" attributes={attributes} />
        </div>
    );
}

registerBlockType(metadata.name, {
    ...metadata,
    edit: Edit,
    save: () => null,
});
