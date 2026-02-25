import {__} from '@wordpress/i18n';
import {useEffect, useState} from '@wordpress/element';

import {Column, ColumnEditorOptions, ColumnEvent} from 'primereact/column';
import {DataTable} from 'primereact/datatable';
import {InputText} from 'primereact/inputtext';
import {Panel} from 'primereact/panel';

import {BusyIndicator} from '../../components/shared/BusyIndicator';
import {ServerSetting, ServerSettingFormData, useCreateServerSetting, useServerSettingDefinitions, useServerSettings, useUpdateServerSetting} from '../../../features/server';

interface ServerSettingProps {
    serverId: number;
    isHostedServer: boolean;
    gameKey: string;
}

export const ServerSettingList = ({serverId, isHostedServer, gameKey}: ServerSettingProps) => {

    const {data: serverSettings = [], isLoading} = useServerSettings(serverId);
    const {mutateAsync: createServerSetting} = useCreateServerSetting(serverId);
    const {mutateAsync: updateServerSetting} = useUpdateServerSetting(serverId);
    const settingDefinitions = useServerSettingDefinitions(gameKey);

    const [data, setData] = useState<ServerSetting[]>([]);

    useEffect(() => {
        const settings = [];

        settingDefinitions.forEach((s) => {
            const setting: {
                serverId: number;
                settingName: string;
                settingValue: string;
                isEditEnabled: boolean,
                id?: number
            } = {
                serverId: serverId,
                settingName: s.name,
                settingValue: s.default,
                isEditEnabled: (isHostedServer && s.editableIfHosted) || !isHostedServer
            };
            const savedSetting = serverSettings.find(ss => ss.settingName === s.name);
            if (savedSetting) {
                setting.settingValue = savedSetting.settingValue;
                setting.id = savedSetting.id;
            }
            settings.push(setting);
        });
        setData(settings);

    }, [serverId, isHostedServer, settingDefinitions, serverSettings]);

    const valueEditor = (options: ColumnEditorOptions) => {
        if(!options.rowData['isEditEnabled']) {
            return options.value;
        }
        return <InputText type='text' value={options.value} onChange={(e) => options.editorCallback(e.target.value)}/>;
    };

    const onCellEditComplete = (e: ColumnEvent) => {
        let {rowData, newValue, field, originalEvent: event} = e;
        event.stopPropagation();

        if (field !== 'settingValue' || newValue === rowData[field]) {
            event.preventDefault();
            return;
        }

        rowData[field] = newValue;

        saveSetting(rowData).then(_ => {
        });
    };

    const saveSetting = async (setting: ServerSetting) => {
        const formData: ServerSettingFormData = {
            settingName: setting.settingName,
            settingValue: setting.settingValue
        };

        if (setting.id > 0) {
            await updateServerSetting({id: setting.id, data: formData});
        } else {
            await createServerSetting(formData);
        }
    };

    return (
        <>
            <BusyIndicator isBusy={isLoading}/>
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