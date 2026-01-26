import 'primereact/resources/themes/lara-dark-amber/theme.css';
import 'primeicons/primeicons.css';
import 'primeflex/primeflex.min.css';

import '../../assets/css/fontawesome.css';
import '../../assets/css/solid.css';

import './index.scss';

import domReady from '@wordpress/dom-ready';
import { createRoot } from '@wordpress/element';

import { SimLeagueToolkitApp } from './SimLeagueToolkitApp';

let root: ReturnType<typeof createRoot> | null = null;

const render = (Component: typeof SimLeagueToolkitApp): void => {
    const container = document.getElementById('sltk-admin-root');

    if (!container) {
        return;
    }

    if (!root) {
        root = createRoot(container);
    }

    root.render(<Component />);
};

domReady(() => {
    render(SimLeagueToolkitApp);
});

// HMR support
if (module.hot) {
    module.hot.accept('./SimLeagueToolkitApp', () => {
        const { SimLeagueToolkitApp: NextApp } = require('./SimLeagueToolkitApp');
        render(NextApp);
    });
}