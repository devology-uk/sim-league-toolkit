import {__} from '@wordpress/i18n';

import {Avatar} from 'primereact/avatar';
import {Button} from 'primereact/button';
import {Card} from 'primereact/card';

import {StandaloneEventEntry} from '../../../features/standaloneEvent';

interface StandaloneEventEntrantCardProps {
    entry: StandaloneEventEntry;
    onRequestDelete: (entry: StandaloneEventEntry) => void;
}

export const StandaloneEventEntrantCard = ({entry, onRequestDelete}: StandaloneEventEntrantCardProps) => {
    const displayName = (entry.firstName || entry.lastName)
        ? `${entry.firstName} ${entry.lastName}`.trim()
        : entry.memberName;

    const title = (
        <div className='flex align-items-center gap-2'>
            <Avatar image={entry.avatarUrl} shape='circle' size='large'/>
            <span>{displayName}</span>
        </div>
    );

    const footer = (
        <Button label={__('Remove', 'sim-league-toolkit')} icon='pi pi-times' severity='danger'
                onClick={() => onRequestDelete(entry)} style={{marginLeft: '1rem'}}/>
    );

    return (
        <Card title={title} footer={footer} style={{margin: '1rem', maxWidth: '400px'}}>
            <table className='table-no-border'>
                <tbody>
                <tr>
                    <th scope='row'>{__('Race #', 'sim-league-toolkit')}</th>
                    <td>{entry.raceNumber || '—'}</td>
                </tr>
                <tr>
                    <th scope='row'>{__('Car', 'sim-league-toolkit')}</th>
                    <td>{entry.carName}</td>
                </tr>
                </tbody>
            </table>
        </Card>
    );
};
