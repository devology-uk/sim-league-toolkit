import {ProgressSpinner} from 'primereact/progressspinner';

export const BusySpinner = ({isBusy}) => {
    return (
        <>
            {isBusy &&
                <ProgressSpinner style={{width: '50px', height: '50px'}} strokeWidth='8' fill='var(--surface-ground)'
                                 animationDuration='.5s'/>
            }
        </>);
}