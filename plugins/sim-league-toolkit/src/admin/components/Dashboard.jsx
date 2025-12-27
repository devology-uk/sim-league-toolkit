import {__} from '@wordpress/i18n';

export const Dashboard = () => {
    return (
        <>
            <h3>{__('Welcome to Sim League Toolkit', 'sim-league-toolkit')}</h3>
            <p>
                {__('With this plugin you can manage a sim racing league using any WordPress theme or website.',     'sim-league-toolkit')}
            </p>
            <p>
                {__('Here in the admin panel you can create championships or individual events for any of the supported games and manage the supporting data needed to do so.', 'sim-league-toolkit')}
            </p>
            <p>
                {__('The plugin also provides Gutenburg Blocks that you can add to your WordPress pages to provide you league members with the information they need to go racing and view results and stats.', 'sim-league-toolkit')}
            </p>
        </>
    )
}