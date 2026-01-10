import {ProgressBar} from "primereact/progressbar";


interface BusyIndicatorProps {
    isBusy: boolean;
}

export const BusyIndicator = ({isBusy}: BusyIndicatorProps) => {
    return (
        <>
            {isBusy && <div className="busy-indicator-container">
                <ProgressBar mode="indeterminate" style={{ height: '6px' }} />
            </div>}
        </>
    );
};