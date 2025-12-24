import 'primereact/resources/themes/fluent-light/theme.css';
import 'primeicons/primeicons.css';
import 'primeflex/primeflex.min.css';

import '../../assets/css/fontawesome.css';
import '../../assets/css/solid.css';

import {__} from '@wordpress/i18n';

import {PrimeReactProvider} from 'primereact/api';
import {TabPanel, TabView} from 'primereact/tabview';

import {Notifications} from './components/notifications';
import {Dashboard} from './components/dashboard';
import {GamesList} from './components/gamesList';

export const SimLeagueToolkitApp = () => {
    return (
        <PrimeReactProvider>
            <Notifications/>
            <TabView style={{minWidth: '75rem'}}>
                <TabPanel header={__('Dashboard', 'sim-league-toolkit')} leftIcon='fa-solid fa-guage-high mr-2'>
                    <Dashboard/>
                </TabPanel>
                <TabPanel header={__('Championships', 'sim-league-toolkit')} leftIcon='fa-solid fa-trophy mr-2'>
                </TabPanel>
                <TabPanel header={__('Individual Events', 'sim-league-toolkit')} leftIcon='fa-solid fa-flag-checkered mr-2'>
                </TabPanel>
                <TabPanel header={__('Event Classes', 'sim-league-toolkit')} leftIcon='fa-solid fa-layer-group mr-2'>
                </TabPanel>
                <TabPanel header={__('Games', 'sim-league-toolkit')} leftIcon='fa-solid fa-gamepad mr-2'>
                    <GamesList />
                </TabPanel>
                <TabPanel header={__('Race Numbers', 'sim-league-toolkit')} leftIcon='fa-solid fa-hashtag mr-2'>
                </TabPanel>
                <TabPanel header={__('Rules', 'sim-league-toolkit')} leftIcon='fa-solid fa-scale-balanced mr-2'>
                </TabPanel>
                <TabPanel header={__('Scoring', 'sim-league-toolkit')} leftIcon='fa-solid fa-table-list mr-2'>
                </TabPanel>
                <TabPanel header={__('Servers', 'sim-league-toolkit')} leftIcon='fa-solid fa-server mr-2'>
                </TabPanel>
                <TabPanel header={__('Tools', 'sim-league-toolkit')} leftIcon='fa-solid fa-screwdriver-wrench mr-2'>
                </TabPanel>
            </TabView>
        </PrimeReactProvider>);
}