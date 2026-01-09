
import {__} from '@wordpress/i18n';

import { Button } from 'primereact/button';

export const AddButton = ({onClick}) => {
    return (
        <Button onClick={onClick}>
            {__('Add', 'sim-league-toolkit')}
        </Button>
    );
}