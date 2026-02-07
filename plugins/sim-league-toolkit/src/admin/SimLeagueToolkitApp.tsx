import {__} from '@wordpress/i18n';
import {Menu} from 'primereact/menu';
import {PrimeReactProvider} from 'primereact/api';

import {Notifications} from './components/Notifications';
import {ContentNavigator} from './components/ContentNavigator';
import {HeaderBar} from './components/HeaderBar';
import {useHashState} from './hooks/useHashState';
import {ViewType} from './types/ViewType';
import {ViewConfig} from './types/ViewConfig';

export const SimLeagueToolkitApp = () => {

    const stateKey: string = 'currentView';
    const [currentView, setCurrentView] = useHashState<ViewType>(stateKey, 'dashboard');

    const viewConfigs: ViewConfig[] = [
        {
            label: __('Dashboard', 'sim-league-toolkit'),
            icon: 'fa-solid fa-gauge-high',
            view: 'dashboard'
        },
        {
            label: __('Championships', 'sim-league-toolkit'),
            icon: 'fa-solid fa-trophy',
            view: 'championships',
        },
        {
            label: __('Standalone Events', 'sim-league-toolkit'),
            icon: 'fa-solid fa-flag-checkered',
            view: 'events',
        },
        {
            label: __('Event Classes', 'sim-league-toolkit'),
            icon: 'fa-solid fa-layer-group',
            view: 'eventClasses',
        },
        {
            label: __('Games', 'sim-league-toolkit'),
            icon: 'fa-solid fa-gamepad',
            view: 'games',
        },
        {
            label: __('Rule Sets', 'sim-league-toolkit'),
            icon: 'fa-solid fa-scale-balanced',
            view: 'ruleSets',
        },
        {
            label: __('Scoring Sets', 'sim-league-toolkit'),
            icon: 'fa-solid fa-table-list',
            view: 'scoringSets',
        },
        {
            label: __('Servers', 'sim-league-toolkit'),
            icon: 'fa-solid fa-server',
            view: 'servers',
        }
    ];

    const menuItems = viewConfigs.map(({label, icon, view}) => ({
        label,
        icon,
        command: () => setCurrentView(view),
    }));

    return (
        <PrimeReactProvider>
            <Notifications />
            <HeaderBar/>
            <div className='main-container'>
                <div className='menu-container'>
                    <Menu model={menuItems}/>
                </div>
                <div className='content-container'>
                    <ContentNavigator currentView={currentView}/>
                </div>
            </div>
        </PrimeReactProvider>
    );
};