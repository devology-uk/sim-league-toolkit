import {__} from '@wordpress/i18n';
import {registerBlockType, type BlockEditProps} from '@wordpress/blocks';
import {InspectorControls, useBlockProps} from '@wordpress/block-editor';
import {PanelBody, RangeControl} from '@wordpress/components';
import ServerSideRender from '@wordpress/server-side-render';

import metadata from './block.json';
import './style.scss';

function Edit({attributes, setAttributes}: BlockEditProps<Record<string, unknown>>) {
    const blockProps = useBlockProps();
    const limit = Number(attributes.limit ?? 0);

    return (
        <div {...blockProps}>
            <InspectorControls>
                <PanelBody title={__('Settings', 'sim-league-toolkit')}>
                    <RangeControl
                        label={__('Limit', 'sim-league-toolkit')}
                        help={__('0 = unlimited.', 'sim-league-toolkit')}
                        min={0}
                        max={50}
                        value={limit}
                        onChange={(value) => setAttributes({limit: value ?? 0})}
                    />
                </PanelBody>
            </InspectorControls>
            <ServerSideRender block="sltk/my-trophies" attributes={attributes} />
        </div>
    );
}

registerBlockType(metadata.name, {
    ...metadata,
    edit: Edit,
    save: () => null,
});
