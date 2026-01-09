import {__} from '@wordpress/i18n';

import {Button} from 'primereact/button';

export const SaveButton = ({disabled, onClick}) => {
    return (
        <Button severity='primary' disabled={disabled} onClick={onClick}>
            {__('Save', 'sim-league-toolkit')}
        </Button>
    );
}