import {__} from '@wordpress/i18n';
import {useEffect, useState} from '@wordpress/element';
import apiFetch from '@wordpress/api-fetch';

import {DataTable} from 'primereact/datatable';
import {Column} from 'primereact/column';
import {Panel} from 'primereact/panel';

import {BusySpinner} from './BusySpinner';


export const Games = () => {

    const [isBusy, setIsBusy] = useState(false);
    const [plannedGames, setPlannedGames] = useState([]);
    const [publishedGames, setPublishedGames] = useState([]);

    useEffect(() => {
        loadTableData();
    }, []);


    const loadTableData = () => {
        setIsBusy(true);
        apiFetch({path: '/sltk/v1/game'}).then((r) => {
            console.log(r);
            const published = r.filter((g) => g.published);
            const planned = r.filter((g) => !g.published);
            setPublishedGames(published ?? []);
            setPlannedGames(planned);
            setIsBusy(false);
        });
    };

    const supportsResultUploadTemplate = (game) => {
        return game.supportsResultUpload ? __('Yes', 'sim-league-toolkit') : __('No', 'sim-league-toolkit');
    };

    const supportsLayoutsTemplate = (game) => {
        return game.supportsLayouts ? __('Yes', 'sim-league-toolkit') : __('No', 'sim-league-toolkit');
    };

    return <>
        <Panel header={__('Published Games', 'sim-league-toolkit')}>
            <DataTable value={publishedGames} dataKey='id' paginator rows={10} rowsPerPageOptions={[5, 10, 25]}
                       tableStyle={{minWidth: '60rem', marginTop: '1rem'}}
                       emptyMessage={__('No games found', 'sim-league-toolkit')}>
                <Column field='name' header={__('Name', 'sim-league-toolkit')}/>
                <Column field='latestVersion' header={__('Latest Version', 'sim-league-toolkit')}/>
                <Column field={supportsResultUploadTemplate} header={__('Supports Result Upload', 'sim-league-toolkit')}/>
                <Column field={supportsLayoutsTemplate} header={__('Supports Layouts', 'sim-league-toolkit')}/>
            </DataTable>
        </Panel>
        <Panel header={__('Planned Games', 'sim-league-toolkit')}>
            <DataTable value={plannedGames} dataKey='id' paginator rows={10} rowsPerPageOptions={[5, 10, 25]}
                       tableStyle={{minWidth: '60rem', marginTop: '1rem'}}
                       emptyMessage={__('No games found', 'sim-league-toolkit')}>
                <Column field='name' header={__('Name', 'sim-league-toolkit')}/>
                <Column field='latestVersion' header={__('Latest Version', 'sim-league-toolkit')}/>
                <Column field={supportsResultUploadTemplate} header={__('Supports Result Upload', 'sim-league-toolkit')}/>
                <Column body={supportsLayoutsTemplate} header={__('Supports Layouts', 'sim-league-toolkit')}/>
            </DataTable>
        </Panel>
        <BusySpinner isActive={isBusy}/>

    </>
}