import {__} from '@wordpress/i18n';
import {registerBlockType, type BlockEditProps, type BlockSaveProps} from '@wordpress/blocks';
import {InnerBlocks, InspectorControls, useBlockProps} from '@wordpress/block-editor';
import {PanelBody, TextControl} from '@wordpress/components';
import {useEffect} from '@wordpress/element';

import metadata from './block.json';

function Edit({attributes, setAttributes, clientId}: BlockEditProps<Record<string, unknown>>) {
    const blockProps = useBlockProps();
    const label = String(attributes.label ?? 'Tab');

    useEffect(() => {
        if (!attributes.tabId) {
            setAttributes({tabId: clientId});
        }
    }, [attributes.tabId, clientId, setAttributes]);

    return (
        <div {...blockProps}>
            <InspectorControls>
                <PanelBody title={__('Tab', 'sim-league-toolkit')}>
                    <TextControl
                        label={__('Tab label', 'sim-league-toolkit')}
                        value={label}
                        onChange={(value) => setAttributes({label: value})}
                    />
                </PanelBody>
            </InspectorControls>
            <p className="sltk-tab-editor-label">{label}</p>
            <InnerBlocks />
        </div>
    );
}

function Save({attributes}: BlockSaveProps<Record<string, unknown>>) {
    const blockProps = useBlockProps.save({className: 'sltk-tab'});
    const tabId = String(attributes.tabId ?? '');
    const label = String(attributes.label ?? 'Tab');

    return (
        <div {...blockProps}>
            <button
                type="button"
                className="sltk-tab-button"
                id={`sltk-tab-${tabId}`}
                aria-controls={`sltk-tab-panel-${tabId}`}
                role="tab"
            >
                {label}
            </button>
            <div
                className="sltk-tab-panel"
                id={`sltk-tab-panel-${tabId}`}
                role="tabpanel"
                aria-labelledby={`sltk-tab-${tabId}`}
            >
                <InnerBlocks.Content />
            </div>
        </div>
    );
}

registerBlockType(metadata.name, {
    ...metadata,
    edit: Edit,
    save: Save,
});
