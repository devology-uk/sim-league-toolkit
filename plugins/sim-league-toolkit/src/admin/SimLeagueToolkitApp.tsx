import { __ } from '@wordpress/i18n';
import { useState, useEffect } from 'react';

import { Button } from 'primereact/button';
import {classNames} from 'primereact/utils';
import { Menu } from 'primereact/menu';
import {MenuItem, MenuItemOptions} from 'primereact/menuitem';
import { PrimeReactProvider } from 'primereact/api';
import { Sidebar } from 'primereact/sidebar';
import { Tooltip } from 'primereact/tooltip';

import { Notifications } from './components/Notifications';
import { ContentNavigator } from './components/ContentNavigator';
import { HeaderBar } from './components/HeaderBar';
import { useHashState } from './hooks/useHashState';
import { ViewType } from './types/ViewType';
import { ViewConfig } from './types/ViewConfig';

export const SimLeagueToolkitApp = () => {
    const stateKey: string = 'currentView';
    const [currentView, setCurrentView] = useHashState<ViewType>(stateKey, 'dashboard');
    const [isCollapsed, setIsCollapsed] = useState(false);
    const [isMobile, setIsMobile] = useState(false);
    const [sidebarVisible, setSidebarVisible] = useState(false);

    useEffect(() => {
        const handleResize = () => {
            const width = window.innerWidth;
            setIsMobile(width < 782);
            setIsCollapsed(width < 960);
        };

        handleResize();
        window.addEventListener('resize', handleResize);
        return () => window.removeEventListener('resize', handleResize);
    }, []);

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

    const menuItems = viewConfigs.map(({ label, icon, view }) => ({
        label,
        icon,
        template: (item: MenuItem, options: MenuItemOptions) => (
            <a
                className={options.className}
                onClick={options.onClick}
                data-pr-tooltip={isCollapsed ? label : undefined}
                data-pr-position="right"
            >
                <span className={`${icon} ${options.iconClassName}`} />
                <span className={options.labelClassName}>{label}</span>
            </a>
        ),
        command: () => {
            setCurrentView(view);
            if (isMobile) {
                setSidebarVisible(false);
            }
        },
    }));

    return (
        <PrimeReactProvider>
            <Notifications />
            <HeaderBar
                startContent={
                    isMobile && (
                        <Button
                            icon="fa-solid fa-bars"
                            onClick={() => setSidebarVisible(true)}
                            className="p-button-text mobile-menu-toggle"
                            aria-label="Toggle menu"
                        />
                    )
                }
            />
            <div className="main-container">
                <Tooltip target="[data-pr-tooltip]" showDelay={150} />

                {/* Desktop/Tablet Menu */}
                {!isMobile && (
                    <div className={classNames('menu-container', { 'collapsed': isCollapsed })}>
                        <Menu model={menuItems} />
                    </div>
                )}

                {/* Mobile Sidebar */}
                {isMobile && (
                    <Sidebar
                        visible={sidebarVisible}
                        onHide={() => setSidebarVisible(false)}
                        className="mobile-sidebar"
                        position="left"
                    >
                        <Menu model={menuItems} />
                    </Sidebar>
                )}

                <div className="content-container">
                    <ContentNavigator currentView={currentView} />
                </div>
            </div>
        </PrimeReactProvider>
    );
};