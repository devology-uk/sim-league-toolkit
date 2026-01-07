import {__} from '@wordpress/i18n';
import {useEffect, useState} from '@wordpress/element';
import apiFetch from '@wordpress/api-fetch';

import {Column} from 'primereact/column';
import {DataTable} from 'primereact/datatable';
import {InputText} from 'primereact/inputtext';
import {Panel} from 'primereact/panel';

import {getServerSettings} from '../../services/ServerSettingProvider';

import {BusySpinner} from '../shared/BusySpinner';

export const ServerSettingList = ({serverId, gameKey}) => {
    const [isBusy, setIsBusy] = useState(false);
    const [data, setData] = useState([]);


    useEffect(() => {
        loadData();
    }, [serverId, gameKey]);

    const loadData = () => {
        setIsBusy(true);
        const settings = [];
        const serverSettings = getServerSettings(gameKey);
        serverSettings.forEach((s) => {
            settings.push( {
                serverId: serverId,
                settingName: s.name,
                settingValue: s.default,
                isEditEnabled: s.editbleIfHosted
            });
        })
        apiFetch({
            path: `/sltk/v1/server/${serverId}/settings`,
            method: 'GET',
        }).then((r) => {
            settings.forEach(s => {
                const savedSetting = r.find(i => i.settingName === s.settingName);
                if(savedSetting) {
                    s.settingValue = savedSetting.settingValue;
                    s.id = savedSetting.id;
                }
            })
            setData(settings);
            setIsBusy(false);
        });
    }

    const valueEditor = (options) => {
        console.log(options);
        return <InputText type='text' value={options.value} onChange={(e) => options.editorCallback(e.target.value)} />;
    };

    const onCellEditComplete = (e) => {
        let {rowData, newValue, field, originalEvent: event} = e;
        event.stopPropagation();

        if (field !== 'settingValue' || newValue === rowData[field] || newValue.length < 1) {
            event.preventDefault();
            return;
        }

        rowData[field] = newValue;

        saveSetting(rowData);
    };

    const saveSetting = (setting) => {
        setIsBusy(true);
        apiFetch({
            path: `sltk/v1/server/settings`,
            method: 'POST',
            data: setting,
        }).then(() => {
            loadData();
        });
    }

    return (
        <div className='flex flex-column align-items-start gap-2'>
            <p style={{maxWidth: '400px'}}>
                {__('The game supports settings that can be configured through files or via an admin console provided by the hosting provider.', 'sim-league-toolkit') + ' '}
                {__('To edit a setting double click in the Value column, after making the change press Enter to save it and update the server.', 'sim-league-toolkit') + ' '}
            </p>
            <Panel header={__('Settings', 'sim-league-toolkit')}>
                <DataTable value={data} size='small'
                           emptyMessage={__('No servers found', 'sim-league-toolkit')}
                           paginator rows={10}
                           style={{minWidth: '400px'}}
                           editMode='cell'>
                    <Column field='settingName' header='Setting'/>
                    <Column field='settingValue' header='Value' key='settingValue'
                            editor={valueEditor}
                            onCellEditComplete={onCellEditComplete}/>
                </DataTable>
            </Panel>
            <BusySpinner isActive={isBusy}/>
        </div>
    )
}