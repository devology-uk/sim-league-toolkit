
import {__} from '@wordpress/i18n';
import {useState} from '@wordpress/element';

import {PrimeReactProvider} from 'primereact/api';

import {Notifications} from './components/Notifications';
import {Menu} from 'primereact/menu';
import {ContentNavigator} from './components/ContentNavigator';
import {HeaderBar} from './components/HeaderBar';

export const SimLeagueToolkitApp = () => {

    const [currentView, setCurrentView] = useState('dashboard');

    const menuItems = [
        {
            label: __('Dashboard', 'sim-league-toolkit'),
            icon: 'fa-solid fa-gauge-high',
            command: () => setCurrentView('dashboard'),
        },
        {
            label: __('Championships', 'sim-league-toolkit'),
            icon: 'fa-solid fa-trophy',
            command: () => setCurrentView('championships'),
        },
        {
            label: __('Standalone Events', 'sim-league-toolkit'),
            icon: 'fa-solid fa-flag-checkered',
            command: () => setCurrentView('events'),
        },
        {
            label: __('Event Classes', 'sim-league-toolkit'),
            icon: 'fa-solid fa-layer-group',
            command: () => setCurrentView('eventClasses'),
        },
        {
            label: __('Games', 'sim-league-toolkit'),
            icon: 'fa-solid fa-gamepad',
            command: () => setCurrentView('games'),
        },
        {
            label: __('Rule Sets', 'sim-league-toolkit'),
            icon: 'fa-solid fa-scale-balanced',
            command: () => setCurrentView('ruleSets'),
        },
        {
            label: __('Scoring Sets', 'sim-league-toolkit'),
            icon: 'fa-solid fa-table-list',
            command: () => setCurrentView('scoringSets'),
        },
        {
            label: __('Servers', 'sim-league-toolkit'),
            icon: 'fa-solid fa-server',
            command: () => setCurrentView('servers'),
        }
    ];
    return (
            <PrimeReactProvider>
                <Notifications/>
                <HeaderBar />
                <div className='main-container'>
                    <div className='menu-container'>
                        <Menu model={menuItems} />
                    </div>
                    <div className='content-container'>
                        <ContentNavigator currentView={currentView} />
                    </div>
                </div>
            </PrimeReactProvider>);
}