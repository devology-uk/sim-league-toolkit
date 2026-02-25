import {ProgressSpinner} from 'primereact/progressspinner';

interface BusySpinnerProps {
    isBusy: boolean;
}

export const BusySpinner = ({isBusy}: BusySpinnerProps) => {
    return (
        <>
            {isBusy &&
                <div className='busy-spinner-container'>
                    <ProgressSpinner style={{width: '50px', height: '50px', margin: "auto"}} strokeWidth='8' fill='var(--surface-ground)'
                                     animationDuration='.5s'/>
                </div>
            }
        </>);
}