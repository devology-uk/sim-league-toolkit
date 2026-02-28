import {__} from '@wordpress/i18n';

import {Button} from 'primereact/button';
import {Card} from 'primereact/card';

import {StandaloneEvent} from '../../../features/standaloneEvent';

interface StandaloneEventCardProps {
    standaloneEvent: StandaloneEvent;
    onRequestEdit: (standaloneEvent: StandaloneEvent) => void;
    onRequestDelete: (standaloneEvent: StandaloneEvent) => void;
}

export const StandaloneEventCard = ({standaloneEvent, onRequestEdit, onRequestDelete}: StandaloneEventCardProps) => {

    const eventDate = new Date(standaloneEvent.eventDate).toLocaleDateString();

    const footer = (
        <>
            <Button label={__('Edit', 'sim-league-toolkit')} icon='pi pi-pencil'
                    onClick={() => onRequestEdit(standaloneEvent)}/>
            <Button label={__('Delete', 'sim-league-toolkit')} icon='pi pi-times' severity='danger'
                    onClick={() => onRequestDelete(standaloneEvent)} style={{marginLeft: '1rem'}}/>
        </>
    );

    const header = standaloneEvent.bannerImageUrl
        ? <img alt={__('Banner image', 'sim-league-toolkit')} src={standaloneEvent.bannerImageUrl} height='168px'/>
        : null;

    return (
        <Card title={standaloneEvent.name} subTitle={standaloneEvent.game}
              footer={footer} header={header}
              style={{margin: '1rem', maxWidth: '400px'}}>
            <table className='table-no-border'>
                <tbody>
                <tr>
                    <th scope='row'>{__('Track', 'sim-league-toolkit')}</th>
                    <td>{standaloneEvent.track}</td>
                </tr>
                {standaloneEvent.trackLayoutId && (
                    <tr>
                        <th scope='row'>{__('Track Layout', 'sim-league-toolkit')}</th>
                        <td>{standaloneEvent.trackLayout}</td>
                    </tr>
                )}
                <tr>
                    <th scope='row'>{__('Event Date', 'sim-league-toolkit')}</th>
                    <td>{eventDate}</td>
                </tr>
                {standaloneEvent.startTime && (
                    <tr>
                        <th scope='row'>{__('Start Time', 'sim-league-toolkit')}</th>
                        <td>{standaloneEvent.startTime}</td>
                    </tr>
                )}
                {standaloneEvent.scoringSet && (
                    <tr>
                        <th scope='row'>{__('Scoring Set', 'sim-league-toolkit')}</th>
                        <td>{standaloneEvent.scoringSet}</td>
                    </tr>
                )}
                {standaloneEvent.ruleSetId && (
                    <tr>
                        <th scope='row'>{__('Rule Set', 'sim-league-toolkit')}</th>
                        <td>{standaloneEvent.ruleSet}</td>
                    </tr>
                )}
                <tr>
                    <th scope='row'>{__('Active', 'sim-league-toolkit')}</th>
                    <td>{standaloneEvent.isActive ? __('Yes', 'sim-league-toolkit') : __('No', 'sim-league-toolkit')}</td>
                </tr>
                </tbody>
            </table>
        </Card>
    );
};
