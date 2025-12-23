import {__} from '@wordpress/i18n';
import {
    // eslint-disable-next-line @wordpress/no-unsafe-wp-apis
    __experimentalHeading as Heading,
} from '@wordpress/components';

export const Title = () => {
    return (
        <Heading level={1}>
            {__('Sim League Toolkit', 'sim-league-toolkit')}
        </Heading>
    );
};