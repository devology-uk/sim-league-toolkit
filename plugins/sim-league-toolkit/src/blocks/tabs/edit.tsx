import {registerBlockType, type BlockEditProps, type BlockSaveProps} from '@wordpress/blocks';
import {InnerBlocks, store as blockEditorStore, useBlockProps} from '@wordpress/block-editor';
import {useSelect} from '@wordpress/data';

import metadata from './block.json';
import './style.scss';

const TEMPLATE: Array<[string, Record<string, unknown>]> = [
    ['sltk/tab', {label: 'Current & Recent'}],
    ['sltk/tab', {label: 'Past'}],
];

function Edit({clientId, attributes, setAttributes}: BlockEditProps<Record<string, unknown>>) {
    const blockProps = useBlockProps({className: 'sltk-tabs-editor'});
    const activeTabIndex = Number(attributes.activeTabIndex ?? 0);

    const childBlocks = useSelect(
        (select) => select(blockEditorStore).getBlocks(clientId),
        [clientId]
    );

    return (
        <div {...blockProps}>
            <div className="sltk-tabs-editor-strip" role="tablist">
                {childBlocks.map((block: {clientId: string; attributes: Record<string, unknown>}, index: number) => (
                    <button
                        key={block.clientId}
                        type="button"
                        className="sltk-tabs-editor-tab-button"
                        aria-selected={index === activeTabIndex}
                        onClick={() => setAttributes({activeTabIndex: index})}
                    >
                        {String(block.attributes.label ?? `Tab ${index + 1}`)}
                    </button>
                ))}
            </div>
            <InnerBlocks allowedBlocks={['sltk/tab']} template={TEMPLATE} templateLock={false} />
        </div>
    );
}

function Save({attributes}: BlockSaveProps<Record<string, unknown>>) {
    const blockProps = useBlockProps.save({className: 'sltk-tabs'});
    const activeTabIndex = Number(attributes.activeTabIndex ?? 0);

    return (
        <div {...blockProps} data-active-tab-index={activeTabIndex}>
            <InnerBlocks.Content />
        </div>
    );
}

registerBlockType(metadata.name, {
    ...metadata,
    edit: Edit,
    save: Save,
});
