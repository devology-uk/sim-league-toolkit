import {ProgressSpinner} from 'primereact/progressspinner';

interface BusySpinnerProps {
    isBusy: boolean;
}

export const BusySpinner = ({isBusy}: BusySpinnerProps) => {
    return (
        <>
            {isBusy &&
                <ProgressSpinner style={{width: '50px', height: '50px'}} strokeWidth='8' fill='var(--surface-ground)'
                                 animationDuration='.5s'/>
            }
        </>);
}