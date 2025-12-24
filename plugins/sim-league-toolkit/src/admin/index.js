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