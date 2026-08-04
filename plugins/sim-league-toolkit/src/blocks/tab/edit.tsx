import {__} from '@wordpress/i18n';
import {registerBlockType, type BlockEditProps, type BlockSaveProps} from '@wordpress/blocks';
import {InnerBlocks, InspectorControls, store as blockEditorStore, useBlockProps} from '@wordpress/block-editor';
import {PanelBody, TextControl} from '@wordpress/components';
import {useEffect} from '@wordpress/element';
import {useSelect} from '@wordpress/data';

import metadata from './block.json';

function Edit({attributes, setAttributes, clientId, context}: BlockEditProps<Record<string, unknown>>) {
    const blockProps = useBlockProps();
    const label = String(attributes.label ?? 'Tab');
    const activeTabIndex = Number(context['sltk/activeTabIndex'] ?? 0);

    const ownIndex = useSelect(
        (select) => select(blockEditorStore).getBlockIndex(clientId),
        [clientId]
    );
    const isActive = ownIndex === activeTabIndex;

    useEffect(() => {
        if (!attributes.tabId) {
            setAttributes({tabId: clientId});
        }
    }, [attributes.tabId, clientId, setAttributes]);

    return (
        <div {...blockProps} style={isActive ? undefined : {display: 'none'}}>
            <InspectorControls>
                <PanelBody title={__('Tab', 'sim-league-toolkit')}>
                    <TextControl
                        label={__('Tab label', 'sim-league-toolkit')}
                        value={label}
                        onChange={(value) => setAttributes({label: value})}
                    />
                </PanelBody>
            </InspectorControls>
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
