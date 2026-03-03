import {__} from '@wordpress/i18n';
import {useState} from '@wordpress/element';

import {Button} from 'primereact/button';
import {DataView} from 'primereact/dataview';
import {Dropdown, DropdownChangeEvent} from 'primereact/dropdown';

import {
    StandaloneEventEntry,
    StandaloneEventEntryFormData,
    useStandaloneEventEntries,
    useStandaloneEventClasses,
    useCreateStandaloneEventEntry,
    useDeleteStandaloneEventEntry,
} from '../../../features/standaloneEvent';
import {useMembers} from '../../../features/member';
import {useCars} from '../../../features/game';
import {ListItem} from '../../types/ListItem';
import {StandaloneEventEntrantCard} from './StandaloneEventEntrantCard';

interface StandaloneEventEntrantsProps {
    standaloneEventId: number;
    gameId: number;
}

export const StandaloneEventEntrants = ({standaloneEventId, gameId}: StandaloneEventEntrantsProps) => {
    const {data: entries, isLoading: entriesLoading} = useStandaloneEventEntries(standaloneEventId);
    const {data: members = [], isLoading: membersLoading} = useMembers();
    const {data: eventClasses = []} = useStandaloneEventClasses(standaloneEventId);
    const {mutateAsync: createEntry, isPending: isCreating} = useCreateStandaloneEventEntry(standaloneEventId);
    const {mutateAsync: deleteEntry} = useDeleteStandaloneEventEntry(standaloneEventId);

    const [selectedMemberId, setSelectedMemberId] = useState(0);
    const [selectedClassEventClassId, setSelectedClassEventClassId] = useState(0);
    const [selectedCarId, setSelectedCarId] = useState(0);

    const selectedClass = eventClasses.find(c => c.eventClassId === selectedClassEventClassId) ?? null;
    const isSingleCarClass = selectedClass?.isSingleCarClass ?? false;

    const {data: cars = []} = useCars(
        selectedClass && !isSingleCarClass ? gameId : 0,
        selectedClass?.carClass
    );

    const resolvedCarId = isSingleCarClass
        ? (selectedClass?.singleCarId ?? 0)
        : selectedCarId;

    const canAdd = selectedMemberId > 0 && selectedClassEventClassId > 0 && resolvedCarId > 0;

    const memberOptions: ListItem[] = ([{value: 0, label: __('Select member...', 'sim-league-toolkit')}] as ListItem[])
        .concat(members.map(m => ({value: m.id, label: m.displayName})));

    const classOptions: ListItem[] = ([{value: 0, label: __('Select class...', 'sim-league-toolkit')}] as ListItem[])
        .concat(eventClasses.map(c => ({value: c.eventClassId, label: c.name})));

    const carOptions: ListItem[] = ([{value: 0, label: __('Select car...', 'sim-league-toolkit')}] as ListItem[])
        .concat(cars.map(c => ({value: c.id, label: c.name})));

    const onClassChange = (e: DropdownChangeEvent) => {
        setSelectedClassEventClassId(e.value);
        setSelectedCarId(0);
    };

    const onAdd = async () => {
        if (!canAdd) {
            return;
        }

        const formData: StandaloneEventEntryFormData = {
            userId: selectedMemberId,
            carId: resolvedCarId,
            eventClassId: selectedClassEventClassId,
        };

        await createEntry(formData);

        setSelectedMemberId(0);
        setSelectedClassEventClassId(0);
        setSelectedCarId(0);
    };

    const onDelete = async (entry: StandaloneEventEntry) => {
        await deleteEntry(entry.id);
    };

    const itemTemplate = (entry: StandaloneEventEntry) => (
        <StandaloneEventEntrantCard key={entry.id} entry={entry} onRequestDelete={onDelete}/>
    );

    const isLoading = entriesLoading || membersLoading || isCreating;

    if (eventClasses.length === 0) {
        return (
            <p>{__('You must assign at least one class in the Classes tab before adding entrants.', 'sim-league-toolkit')}</p>
        );
    }

    return (
        <>
            <div className='flex flex-row flex-wrap align-items-end gap-2' style={{marginBottom: '1rem'}}>
                <div className='flex flex-column gap-1'>
                    <label htmlFor='entrant-member'>{__('Member', 'sim-league-toolkit')}</label>
                    <Dropdown id='entrant-member'
                              value={selectedMemberId}
                              options={memberOptions}
                              onChange={(e) => setSelectedMemberId(e.value)}
                              optionLabel='label'
                              optionValue='value'
                              disabled={isLoading}
                              style={{minWidth: '200px'}}/>
                </div>
                <div className='flex flex-column gap-1'>
                    <label htmlFor='entrant-class'>{__('Class', 'sim-league-toolkit')}</label>
                    <Dropdown id='entrant-class'
                              value={selectedClassEventClassId}
                              options={classOptions}
                              onChange={onClassChange}
                              optionLabel='label'
                              optionValue='value'
                              disabled={isLoading}
                              style={{minWidth: '200px'}}/>
                </div>
                {selectedClass && !isSingleCarClass && (
                    <div className='flex flex-column gap-1'>
                        <label htmlFor='entrant-car'>{__('Car', 'sim-league-toolkit')}</label>
                        <Dropdown id='entrant-car'
                                  value={selectedCarId}
                                  options={carOptions}
                                  onChange={(e) => setSelectedCarId(e.value)}
                                  optionLabel='label'
                                  optionValue='value'
                                  disabled={isLoading}
                                  style={{minWidth: '200px'}}/>
                    </div>
                )}
                <Button icon='pi pi-plus' size='small' onClick={onAdd} disabled={!canAdd || isLoading}/>
            </div>

            <DataView value={entries}
                      itemTemplate={itemTemplate}
                      layout='grid'
                      header={__('Entrants', 'sim-league-toolkit')}
                      emptyMessage={__('No entrants have been added.', 'sim-league-toolkit')}
                      style={{marginRight: '1rem'}}/>
        </>
    );
};
