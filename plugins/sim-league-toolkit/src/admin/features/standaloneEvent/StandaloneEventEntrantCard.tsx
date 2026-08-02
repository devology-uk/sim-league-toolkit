import {StandaloneEventEntry} from '../../../features/standaloneEvent';
import {EntrantCard} from '../../components/EntrantCard';

interface StandaloneEventEntrantCardProps {
    entry: StandaloneEventEntry;
    onRequestDelete: (entry: StandaloneEventEntry) => void;
}

export const StandaloneEventEntrantCard = ({entry, onRequestDelete}: StandaloneEventEntrantCardProps) => (
    <EntrantCard entry={entry} onRequestDelete={onRequestDelete}/>
);
