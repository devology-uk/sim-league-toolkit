import {Dashboard} from './Dashboard';
import {Games} from './games/Games';
import {RuleSets} from './rules/RuleSets';
import {Championships} from './Championships';
import {Events} from './Events';
import {EventClasses} from './eventClasses/EventClasses';
import {RaceNumbers} from './RaceNumbers';
import {ScoringSets} from './ScoringSets';
import {Servers} from './Servers';
import {Tools} from './Tools';

export const ContentNavigator = ({currentView}) => {
    switch (currentView) {
        case 'championships':
            return <Championships/>;
        case 'events':
            return <Events/>;
        case 'eventClasses':
            return <EventClasses/>;
        case 'games':
            return <Games/>
        case 'raceNumbers':
            return <RaceNumbers/>;
        case 'ruleSets':
            return <RuleSets/>
        case 'scoringSets':
            return <ScoringSets/>;
        case 'servers':
            return <Servers/>;
        case 'tools':
            return <Tools/>;
        default:
            return <Dashboard/>
    }

}