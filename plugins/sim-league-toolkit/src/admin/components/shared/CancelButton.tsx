import {__} from '@wordpress/i18n';

import {Button} from 'primereact/button';

export const CancelButton = ({onCancel, disabled}) => {
    return (
        <Button severity='secondary' onClick={onCancel} disabled={disabled} style={{marginLeft: '.5rem', marginRight: '.5rem'}}>
            {__('Cancel', 'sim-league-toolkit')}
        </Button>
    );
}