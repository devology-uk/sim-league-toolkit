import {useEffect, useState} from "@wordpress/element";
import apiFetch from '@wordpress/api-fetch';

const RaceNumbersActiveAllocations = () => {
  const [raceNumbers, setRaceNumbers] = useState([]);
  useEffect(() => {
    async function loadRaceNumbers() {
      const data = await apiFetch(
        {
          path: 'sltk/v1/race-numbers',
          method: 'GET'
        }
      );

      setRaceNumbers(data);
    }

    loadRaceNumbers();
  });

  return (
    <div>
      Active Allocations {raceNumbers.length}
    </div>
  )
}

export {RaceNumbersActiveAllocations};