import {__} from '@wordpress/i18n';
import {ReactNode} from 'react';

import {Avatar} from 'primereact/avatar';
import {Button} from 'primereact/button';
import {Card} from 'primereact/card';
import {Tag} from 'primereact/tag';

interface EntrantCardEntry {
    avatarUrl: string;
    carName: string;
    firstName: string;
    lastName: string;
    memberName: string;
    raceNumber: number;
    status: string;
}

interface EntrantCardProps<TEntry extends EntrantCardEntry> {
    entry: TEntry;
    onRequestDelete: (entry: TEntry) => void;
    classRow?: ReactNode;
}

export const EntrantCard = <TEntry extends EntrantCardEntry, >({entry, onRequestDelete, classRow}: EntrantCardProps<TEntry>) => {
    const displayName = (entry.firstName || entry.lastName)
        ? `${entry.firstName} ${entry.lastName}`.trim()
        : entry.memberName;

    const isWaitlisted = entry.status === 'waitlisted';

    const title = (
        <div className='flex align-items-center justify-content-between gap-2'>
            <div className='flex align-items-center gap-2'>
                <Avatar image={entry.avatarUrl} shape='circle' size='large'/>
                <span>{displayName}</span>
            </div>
            {isWaitlisted &&
                <Tag value={__('Waitlisted', 'sim-league-toolkit')} severity='warning'/>}
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
                {classRow}
                <tr>
                    <th scope='row'>{__('Car', 'sim-league-toolkit')}</th>
                    <td>{entry.carName}</td>
                </tr>
                </tbody>
            </table>
        </Card>
    );
};
