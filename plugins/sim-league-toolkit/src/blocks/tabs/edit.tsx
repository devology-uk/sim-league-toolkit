import {registerBlockType} from '@wordpress/blocks';
import {InnerBlocks, useBlockProps} from '@wordpress/block-editor';

import metadata from './block.json';
import './style.scss';

const TEMPLATE: Array<[string, Record<string, unknown>]> = [
    ['sltk/tab', {label: 'Current & Recent'}],
    ['sltk/tab', {label: 'Past'}],
];

function Edit() {
    const blockProps = useBlockProps({className: 'sltk-tabs-editor'});

    return (
        <div {...blockProps}>
            <InnerBlocks allowedBlocks={['sltk/tab']} template={TEMPLATE} templateLock={false} />
        </div>
    );
}

function Save() {
    const blockProps = useBlockProps.save({className: 'sltk-tabs'});

    return (
        <div {...blockProps}>
            <InnerBlocks.Content />
        </div>
    );
}

registerBlockType(metadata.name, {
    ...metadata,
    edit: Edit,
    save: Save,
});
