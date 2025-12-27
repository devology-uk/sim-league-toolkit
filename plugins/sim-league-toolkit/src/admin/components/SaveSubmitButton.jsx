import {__} from '@wordpress/i18n';

import {Button} from 'primereact/button';

export const SaveSubmitButton = ({disabled, name = 'submitForm'}) => {
    return (
        <Button severity='primary' type='submit' disabled={disabled} name={name}>
            {__('Save', 'sim-league-toolkit')}
        </Button>
    );
}