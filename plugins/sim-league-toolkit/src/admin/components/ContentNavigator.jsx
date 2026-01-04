import {Dashboard} from './dashboard/Dashboard';
import {Games} from './games/Games';
import {RuleSets} from './rules/RuleSets';
import {Championships} from './championships/Championships';
import {Events} from './events/Events';
import {EventClasses} from './eventClasses/EventClasses';
import {ScoringSets} from './scoringSets/ScoringSets';
import {Servers} from './servers/Servers';
import {Tools} from './tools/Tools';

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