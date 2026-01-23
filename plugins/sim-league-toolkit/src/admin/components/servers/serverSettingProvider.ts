import {ServerSettingDefinition} from "../../types/ServerSettingDefinition";

const accSettings: ServerSettingDefinition[] = [
    {name: "tcpPort", default: '9201', editableIfHosted: false},
    {name: "udpPort", default: '9201', editableIfHosted: false},
    {name: "lanDiscovery", default: '1', editableIfHosted: true},
    {name: "maxCarSlots", default: '85', editableIfHosted: false},
    {name: "maxConnections", default: '85', editableIfHosted: false},
    {name: "publicIP", default: '', editableIfHosted: false},
    {name: "adminPassword", default: '', editableIfHosted: true},
    {name: "password", default: '', editableIfHosted: true},
    {name: "spectatorPassword", default: '', editableIfHosted: true},
    {name: "centralEntryListPath", default: '', editableIfHosted: true},
    {name: "dumpLeaderboards", default: '1', editableIfHosted: true},
    {name: "ignorePrematureDisconnects", default: '1', editableIfHosted: true},
    {name: "randomizeTrackWhenEmpty", default: '1', editableIfHosted: true},
    {name: "registerToLobby", default: '1', editableIfHosted: true},
    {name: "serverName", default: '', editableIfHosted: true},
];
const ams2Settings: ServerSettingDefinition[] = [];
const lmuSettings: ServerSettingDefinition[] = [];

export const getServerSettings = (gameKey: string): ServerSettingDefinition[] => {
    switch (gameKey) {
        case 'ACC':
            return accSettings;
        case 'AMS2':
            return ams2Settings;
        case 'LMU':
            return lmuSettings;
        default:
            return []
    }
}