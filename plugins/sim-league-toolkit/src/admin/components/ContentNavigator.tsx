import {Dashboard} from './dashboard/Dashboard';
import {Games} from './games/Games';
import {RuleSets} from './rules/RuleSets';
import {Championships} from './championships/Championships';
import {Events} from './events/Events';
import {EventClasses} from './eventClasses/EventClasses';
import {ScoringSets} from './scoringSets/ScoringSets';
import {Servers} from './servers/Servers';
import {ViewType} from '../types/ViewType';

interface ContentNavigatorProps {
    currentView:ViewType;
}
export const ContentNavigator = ({currentView}: ContentNavigatorProps) => {
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
        default:
            return <Dashboard/>
    }

}