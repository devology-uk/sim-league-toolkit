import {__} from '@wordpress/i18n';
import apiFetch from '@wordpress/api-fetch';
import {useEffect, useState} from '@wordpress/element';

import {Column, ColumnEditorOptions, ColumnEvent} from 'primereact/column';
import {DataTable} from 'primereact/datatable';
import {InputText} from 'primereact/inputtext';
import {Panel} from 'primereact/panel';

import {BusyIndicator} from '../shared/BusyIndicator';
import {getServerSettings} from './serverSettingProvider';
import {HttpMethod} from '../shared/HttpMethod';
import {ServerSetting} from '../../types/ServerSetting';
import {serverSettingsGetRoute, serverSettingsPostRoute} from '../../api/routes/serverApiRoutes';

interface ServerSettingProps {
    serverId: number;
    gameKey: string;
}

export const ServerSettingList = ({serverId, gameKey}: ServerSettingProps) => {
    const [isBusy, setIsBusy] = useState(false);
    const [data, setData] = useState<ServerSetting[]>([]);

    useEffect(() => {
        loadData();
    }, [serverId, gameKey]);

    const loadData = () => {
        setIsBusy(true);
        const settings = [];
        const serverSettings = getServerSettings(gameKey);
        serverSettings.forEach((s) => {
            settings.push({
                              serverId: serverId,
                              settingName: s.name,
                              settingValue: s.default,
                              isEditEnabled: s.editableIfHosted
                          });
        });
        apiFetch({
                     path: serverSettingsGetRoute(serverId),
                     method: HttpMethod.GET,
                 }).then((r: ServerSetting[]) => {
            settings.forEach(s => {
                const savedSetting = r.find(i => i.settingName === s.settingName);
                if (savedSetting) {
                    s.settingValue = savedSetting.settingValue;
                    s.id = savedSetting.id;
                }
            });
            setData(settings);
            setIsBusy(false);
        });
    };

    const valueEditor = (options: ColumnEditorOptions) => {
        return <InputText type='text' value={options.value} onChange={(e) => options.editorCallback(e.target.value)}/>;
    };

    const onCellEditComplete = (e: ColumnEvent) => {
        let {rowData, newValue, field, originalEvent: event} = e;
        event.stopPropagation();

        if (field !== 'settingValue' || newValue === rowData[field] || newValue.length < 1) {
            event.preventDefault();
            return;
        }

        rowData[field] = newValue;

        saveSetting(rowData);
    };

    const saveSetting = (setting: ServerSetting) => {
        setIsBusy(true);
        apiFetch({
                     path: serverSettingsPostRoute(),
                     method: HttpMethod.POST,
                     data: setting,
                 }).then(() => {
            loadData();
        });
    };

    return (
        <>
            <BusyIndicator isBusy={isBusy}/>
            <div className='flex flex-column align-items-start gap-2'>
                <p style={{maxWidth: '400px'}}>
                    {__('The game supports settings that can be configured through files or via an admin console provided by the hosting provider.',
                        'sim-league-toolkit') + ' '}
                    {__('To edit a setting double click in the Value column, after making the change press Enter to save it and update the server.',
                        'sim-league-toolkit') + ' '}
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
            </div>
        </>
    );
};