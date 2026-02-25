import {__} from '@wordpress/i18n';

import {Button} from 'primereact/button';

interface AddButtonProps {
    onClick: () => void;
}

export const AddButton = ({onClick}: AddButtonProps) => {
    return (
        <Button onClick={onClick}>
            {__('Add', 'sim-league-toolkit')}
        </Button>
    );
}