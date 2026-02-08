import { __ } from '@wordpress/i18n';
import { Toolbar } from 'primereact/toolbar';
import { ReactNode } from 'react';

// @ts-ignore
import logoImage from '../../../assets/images/logo-small-transparent.png';

interface HeaderBarProps {
    startContent?: ReactNode;
}

export const HeaderBar = ({ startContent }: HeaderBarProps) => {
    const defaultStartContent = (
        <>
            {startContent}
            <img src={logoImage} className='logo' alt={__('Sim League Toolkit logo', 'sim-league-toolkit')} />
            <span className='header-text'>{__('Sim League Toolkit', 'sim-league-toolkit')}</span>
        </>
    );

    const endContent = (
        <>
            <h3>Socials</h3>
        </>
    );

    return (
        <Toolbar start={defaultStartContent} end={endContent} />
    );
};