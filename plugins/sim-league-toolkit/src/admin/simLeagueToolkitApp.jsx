import 'primereact/resources/themes/fluent-light/theme.css';
import 'primeicons/primeicons.css';
import 'primeflex/primeflex.min.css';

import {__} from '@wordpress/i18n';
import apiFetch from '@wordpress/api-fetch';

import {PrimeReactProvider} from 'primereact/api';

import {Notifications} from './components/notifications';
import {Title} from './components/title';

export const SimLeagueToolkitApp = () => {
    return (
        <PrimeReactProvider>
            <Title/>
            <Notifications/>
            {/*<TabView style={{minWidth: '75rem'}}>*/}
            {/*    <TabPanel header={__('Settings', 'racket-club')} leftIcon='pi pi-cog mr-2'>*/}
            {/*        <SettingsTab settings={settings} onSettingsChanged={onSettingsChanged}/>*/}
            {/*    </TabPanel>*/}
            {/*    <TabPanel header={__('Locations', 'racket-club')} leftIcon='pi pi-map-marker mr-2'>*/}
            {/*        <LocationsTab settings={settings}/>*/}
            {/*    </TabPanel>*/}
            {/*    <TabPanel header={__('Events', 'racket-club')} leftIcon='pi pi-calendar mr-2'>*/}
            {/*        <EventsTab settings={settings} />*/}
            {/*    </TabPanel>*/}
            {/*</TabView>*/}
        </PrimeReactProvider>);
}