import {__} from '@wordpress/i18n';

import {Button} from 'primereact/button';

interface SaveButtonProps {
    disabled?: boolean;
    onClick: () => void;
}

export const SaveButton = ({disabled = false, onClick}: SaveButtonProps) => {
    return (
        <Button disabled={disabled} onClick={onClick}>
            {__('Save', 'sim-league-toolkit')}
        </Button>
    );
}