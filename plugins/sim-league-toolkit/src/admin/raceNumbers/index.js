import domReady from '@wordpress/dom-ready';
import {createRoot} from '@wordpress/element';
import {TabPanel} from '@wordpress/components';
import {__} from '@wordpress/i18n';
import {RaceNumbersActiveAllocations} from "./raceNumbersActiveAllocations";
import {RaceNumberPreAllocations} from "./raceNumbersPreAllocations";

const RaceNumbersPage = () => {
  return (
    <div className='wrap'>
      <h2>{__('Race Numbers', 'sim-league-toolkit')} </h2>
      <p>
        {__('Members can select from available race numbers in their User Profile. However, you may need to override their selection, particularly if you are migrating from another platform to Sim League Toolkit.', 'sltk-league-toolkit')}
      </p>
      <p>
        {__('Here you will find the tools to manage race numbers for members.', 'sltk-league-toolkit')}
      </p>
      <TabPanel tabs={[
        {
          name: 'activeAllocations',
          title: __('Active Allocations', 'sim-league-toolkit'),
          className: 'active-allocations',
        }, {
          name: 'preAllocations',
          title: __('Pre-Allocations', 'sim-league-toolkit'),
          className: 'pre-allocations',
        }
      ]}>
        {
          (tab) => {
            if(tab.name === 'activeAllocations') {
              return <RaceNumbersActiveAllocations />;
            } else {
              return <RaceNumberPreAllocations />
            }
          }

        }
      </TabPanel>
    </div>
  );
};

domReady(() => {
  const root = createRoot(
    document.getElementById('race-numbers-root')
  );

  root.render(<RaceNumbersPage />);
});