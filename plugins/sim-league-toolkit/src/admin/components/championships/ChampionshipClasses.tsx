import {__} from "@wordpress/i18n";
import {useState, useEffect} from "@wordpress/element";
import apiFetch from "@wordpress/api-fetch";

import {DataView} from "primereact/dataview";

import {
    championshipClassesGetRoute,
    championshipClassesPostRoute,
    championshipClassDeleteRoute
} from "../shared/ApiRoutes";
import {ChampionshipClass} from "./ChampionshipClass";
import {ChampionshipClassCard} from "./ChampionshipClassCard";
import {HttpMethod} from "../shared/HttpMethod";
import {EventClassSelector} from "../eventClasses/EventClassSelector";
import {Button} from "primereact/button";

interface ChampionshipClassesProps {
    championshipId: number,
    gameId: number,
}

export const ChampionshipClasses = ({championshipId, gameId}: ChampionshipClassesProps) => {
    const [data, setData] = useState<ChampionshipClass[]>([]);
    const [isBusy, setIsBusy] = useState(false);
    const [selectedEventClassId, setSelectedEventClassId] = useState(0);

    useEffect(() => {
        loadData()
    }, [championshipId]);

    const loadData = () => {
        setIsBusy(true);
        apiFetch({
            path: championshipClassesGetRoute(championshipId),
            method: HttpMethod.GET,
        }).then((r: ChampionshipClass[]) => {
            setData(r);
            setIsBusy(false);
        });
    }

    const onDelete = (item: ChampionshipClass) => {
        setIsBusy(true);
        apiFetch({
            path: championshipClassDeleteRoute(item.championshipId, item.eventClassId),
            method: HttpMethod.DELETE,
        }).then((_) => {
            loadData();
        });
    }

    const onSelectEventClass = (itemId: number) => {
        setSelectedEventClassId(itemId);
    }

    const onAssignEventClass = (): void => {
        setIsBusy(true);
        const data = {
            championshipId: championshipId,
            eventClassId: selectedEventClassId,
        }
        apiFetch({
            path: championshipClassesPostRoute(),
            method: HttpMethod.POST,
            data: data,
        }).then((_) => {
            setSelectedEventClassId(0);
            loadData();
            setIsBusy(false);
        });
    }

    const itemTemplate = (item: ChampionshipClass) => {
        return <ChampionshipClassCard championshipClass={item} key={item.eventClassId}
                                      onRequestDelete={onDelete}/>
    }

    return (
        <>
            <p>
                {__('Sim League Toolkit allows you to create multiclass championships.  Here you can assign event classes to the championship.', 'sim-league-toolkit')}
            </p>
            <p>
                {__('When they enter a championship members are prompted to select which class they wish to enter.  You will be able to change that in the entrants section.', 'sim-league-toolkit')}
            </p>

            <div>
                <EventClassSelector onSelectedItemChanged={onSelectEventClass} eventClassId={selectedEventClassId}
                                    gameId={gameId}/>
                <Button onClick={onAssignEventClass} disabled={isBusy || selectedEventClassId === 0}
                        icon='pi pi-plus' size='small'/>
            </div>

            <DataView value={data} itemTemplate={itemTemplate} layout='grid'
                      header={__('Assigned Classes', 'sim-league-toolkit')}
                      emptyMessage={__('No classes have been assigned.', 'sim-league-toolkit')}
                      style={{marginRight: '1rem'}}/>

        </>
    );
};