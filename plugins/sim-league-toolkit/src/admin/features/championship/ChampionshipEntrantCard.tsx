import {__} from '@wordpress/i18n';

import {Button} from 'primereact/button';
import {Card} from 'primereact/card';

import {ChampionshipEntry} from '../../../features/championship';

interface ChampionshipEntrantCardProps {
    entry: ChampionshipEntry;
    onRequestDelete: (entry: ChampionshipEntry) => void;
}

export const ChampionshipEntrantCard = ({entry, onRequestDelete}: ChampionshipEntrantCardProps) => {
    const footer = (
        <Button label={__('Remove', 'sim-league-toolkit')} icon='pi pi-times' severity='danger'
                onClick={() => onRequestDelete(entry)} style={{marginLeft: '1rem'}}/>
    );

    return (
        <Card title={entry.memberName} footer={footer} style={{margin: '1rem', maxWidth: '400px'}}>
            <table className='table-no-border'>
                <tbody>
                <tr>
                    <th scope='row'>{__('Class', 'sim-league-toolkit')}</th>
                    <td>{entry.className}</td>
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
