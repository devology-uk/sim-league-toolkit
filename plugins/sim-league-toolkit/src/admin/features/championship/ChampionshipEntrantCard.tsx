import {__} from '@wordpress/i18n';

import {ChampionshipEntry} from '../../../features/championship';
import {EntrantCard} from '../../components/EntrantCard';

interface ChampionshipEntrantCardProps {
    entry: ChampionshipEntry;
    onRequestDelete: (entry: ChampionshipEntry) => void;
}

export const ChampionshipEntrantCard = ({entry, onRequestDelete}: ChampionshipEntrantCardProps) => (
    <EntrantCard
        entry={entry}
        onRequestDelete={onRequestDelete}
        classRow={
            <tr>
                <th scope='row'>{__('Class', 'sim-league-toolkit')}</th>
                <td>{entry.className}</td>
            </tr>
        }
    />
);
