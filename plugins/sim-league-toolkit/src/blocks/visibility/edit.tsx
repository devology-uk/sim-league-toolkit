import {__} from '@wordpress/i18n';
import {registerBlockType, type BlockEditProps, type BlockSaveProps} from '@wordpress/blocks';
import {InnerBlocks, InspectorControls, useBlockProps} from '@wordpress/block-editor';
import {Notice, PanelBody, __experimentalToggleGroupControl as ToggleGroupControl, __experimentalToggleGroupControlOption as ToggleGroupControlOption} from '@wordpress/components';

import metadata from './block.json';

function Edit({attributes, setAttributes}: BlockEditProps<Record<string, unknown>>) {
    const blockProps = useBlockProps();
    const visibleTo = String(attributes.visibleTo ?? 'everyone');

    return (
        <div {...blockProps}>
            <InspectorControls>
                <PanelBody title={__('Visibility', 'sim-league-toolkit')}>
                    <ToggleGroupControl
                        label={__('Show to', 'sim-league-toolkit')}
                        value={visibleTo}
                        onChange={(value) => setAttributes({visibleTo: value})}
                        isBlock
                    >
                        <ToggleGroupControlOption value="everyone" label={__('Everyone', 'sim-league-toolkit')} />
                        <ToggleGroupControlOption value="loggedIn" label={__('Logged in', 'sim-league-toolkit')} />
                        <ToggleGroupControlOption value="loggedOut" label={__('Logged out', 'sim-league-toolkit')} />
                    </ToggleGroupControl>
                </PanelBody>
            </InspectorControls>
            {visibleTo !== 'everyone' && (
                <Notice status="info" isDismissible={false}>
                    {visibleTo === 'loggedIn'
                        ? __('Only visible to logged-in users on the front end.', 'sim-league-toolkit')
                        : __('Only visible to logged-out users on the front end.', 'sim-league-toolkit')}
                </Notice>
            )}
            <InnerBlocks templateLock={false} />
        </div>
    );
}

function Save() {
    return <InnerBlocks.Content />;
}

registerBlockType(metadata.name, {
    ...metadata,
    edit: Edit,
    save: Save,
});
