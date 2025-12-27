import 'primereact/resources/themes/lara-dark-amber/theme.css';
import 'primeicons/primeicons.css';
import 'primeflex/primeflex.min.css';

import '../../assets/css/fontawesome.css';
import '../../assets/css/solid.css';

import './index.scss';

import domReady from '@wordpress/dom-ready';
import {createRoot} from '@wordpress/element';

import {SimLeagueToolkitApp} from './simLeagueToolkitApp';

domReady(() => {
    const root = createRoot(
        document.getElementById('sltk-admin-root')
    );

    root.render(<SimLeagueToolkitApp />);
});