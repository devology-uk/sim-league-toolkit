import {__} from '@wordpress/i18n';

import logoImage from '../../../assets/images/logo-small-transparent.png';

import {Toolbar} from 'primereact/toolbar';

export const HeaderBar = () => {
    const startContent = (
        <>
            <img src={logoImage} className='logo' alt={__('Sim League Toolkit loge', 'sim-league-toolkit')} />
            <span className='header-text'>Sim League Toolkit</span>
        </>
    );

    const endContent = (
        <>
            <h3>Socials</h3>
        </>
    )
    return (
        <Toolbar start={startContent} end={endContent}/>
    )
}