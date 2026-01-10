import {__} from '@wordpress/i18n';

import {Button} from 'primereact/button';
import {Card} from 'primereact/card';
import {EventClass} from "./EventClass";

interface EventClassCardProps {
    eventClass: EventClass;
    onRequestEdit: (item: EventClass) => void;
    onRequestDelete: (item: EventClass) => void;
}

export const EventClassCard = ({eventClass, onRequestEdit, onRequestDelete}: EventClassCardProps) => {

    const footer = (
        <>
            {!eventClass.isBuiltIn && (
                <Button label={__('Edit', 'sim-league-toolkit')} icon='pi pi-pencil'
                        onClick={() => onRequestEdit(eventClass)}/>)}
            {!eventClass.isBuiltIn && !eventClass.isInUse && (
                <Button label={__('Delete', 'sim-league-toolkit')} icon='pi pi-times' severity='danger'
                        onClick={() => onRequestDelete(eventClass)} style={{marginLeft: '1rem'}}/>)
            }
        </>
    );

    return (
        <Card title={eventClass.name} subTitle={eventClass.game}
              footer={footer}
              style={{margin: '1rem', maxWidth: '400px'}}>
            <table className='table-no-border'>
                <tbody>
                <tr>
                    <th scope='row'>{__('Car Class', 'sim-league-toolkit')}</th>
                    <td>{eventClass.carClass}</td>
                </tr>
                <tr>
                    <th scope='row'>{__('Driver Category', 'sim-league-toolkit')}</th>
                    <td>{eventClass.driverCategory}</td>
                </tr>
                <tr>
                    <th scope='row'>{__('Is Single Car Class', 'sim-league-toolkit')}</th>
                    <td>{eventClass.isSingleCarClass ? __('Yes', 'sim-league-toolkit') : __('No', 'sim-league-toolkit')}</td>
                </tr>
                {eventClass.isSingleCarClass &&
                    <tr>
                        <th scope='row'>{__('Car', 'sim-league-toolkit')}</th>
                        <td>{eventClass.singleCarName}</td>
                    </tr>
                }
                <tr>
                    <th scope='row'>{__('Is Built In', 'sim-league-toolkit')}</th>
                    <td>{eventClass.isBuiltIn ? __('Yes', 'sim-league-toolkit') : __('No', 'sim-league-toolkit')}</td>
                </tr>
                <tr>
                    <th scope='row'>{__('Is In Use', 'sim-league-toolkit')}</th>
                    <td>{eventClass.isInUse ? __('Yes', 'sim-league-toolkit') : __('No', 'sim-league-toolkit')}</td>
                </tr>
                </tbody>
            </table>
        </Card>
    )
}